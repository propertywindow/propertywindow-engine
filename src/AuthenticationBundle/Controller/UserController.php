<?php declare(strict_types=1);

namespace AuthenticationBundle\Controller;

use AppBundle\Controller\BaseController;
use AuthenticationBundle\Exceptions\NotAuthorizedException;
use AuthenticationBundle\Exceptions\UserAlreadyExistException;
use AuthenticationBundle\Exceptions\UserNotFoundException;
use AuthenticationBundle\Service\User\Mapper;
use Exception;
use InvalidArgumentException;
use Swift_Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Models\JsonRpc\Error;
use AppBundle\Models\JsonRpc\Response;
use AppBundle\Exceptions\CouldNotAuthenticateUserException;
use AppBundle\Exceptions\JsonRpc\CouldNotParseJsonRequestException;
use AppBundle\Exceptions\JsonRpc\InvalidJsonRpcMethodException;
use AppBundle\Exceptions\JsonRpc\InvalidJsonRpcRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * @Route(service="user_controller")
 */
class UserController extends BaseController
{
    /**
     * @Route("/authentication/user" , name="user")
     *
     * @param Request $httpRequest
     *
     * @return HttpResponse
     */
    public function requestHandler(Request $httpRequest)
    {
        $id = null;
        try {
            list($id, $userId, $method, $parameters) = $this->prepareRequest($httpRequest);

            $jsonRpcResponse = Response::success($id, $this->invoke($userId, $method, $parameters));
        } catch (CouldNotParseJsonRequestException $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::PARSE_ERROR, $ex->getMessage()));
        } catch (InvalidJsonRpcRequestException $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::INVALID_REQUEST, $ex->getMessage()));
        } catch (InvalidJsonRpcMethodException $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::METHOD_NOT_FOUND, $ex->getMessage()));
        } catch (InvalidArgumentException $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::INVALID_PARAMS, $ex->getMessage()));
        } catch (CouldNotAuthenticateUserException $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::USER_NOT_AUTHENTICATED, $ex->getMessage()));
        } catch (UserNotFoundException $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::USER_NOT_FOUND, $ex->getMessage()));
        } catch (Exception $ex) {
            $jsonRpcResponse = Response::failure($id, new Error(self::INTERNAL_ERROR, $ex->getMessage()));
        }

        return $this->createResponse($jsonRpcResponse);
    }

    /**
     * @param int    $userId
     * @param string $method
     * @param array  $parameters
     *
     * @return array
     * @throws InvalidJsonRpcMethodException
     * @throws UserNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    private function invoke(int $userId, string $method, array $parameters = [])
    {
        switch ($method) {
            case "getUser":
                return $this->getUserById($userId, $parameters);
            case "getUsers":
                return $this->getUsers($userId);
            case "getColleagues":
                return $this->getColleagues($userId);
            case "createUser":
                return $this->createUser($userId, $parameters);
            case "updateUser":
                return $this->updateUser($userId, $parameters);
            case "setPassword":
                return $this->setPassword($userId, $parameters);
            case "disableUser":
                return $this->disableUser($userId, $parameters);
            case "deleteUser":
                return $this->deleteUser($userId, $parameters);
        }

        throw new InvalidJsonRpcMethodException("Method $method does not exist");
    }


    /**
     * @param int   $userId
     * @param array $parameters
     *
     * @return array
     * @throws NotAuthorizedException
     */
    private function getUserById(int $userId, array $parameters)
    {
        if (!array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException("No argument provided");
        }

        $id      = (int)$parameters['id'];
        $user    = $this->userService->getUser($id);
        $getUser = $this->userService->getUser($userId);

        if ($getUser->getAgent()->getId() !== $user->getAgent()->getId()) {
            throw new NotAuthorizedException($userId);
        }

        return Mapper::fromUser($getUser);
    }

    /**
     * @param int $userId
     *
     * @return array
     * @throws NotAuthorizedException
     */
    private function getUsers(int $userId)
    {
        $user          = $this->userService->getUser($userId);
        $adminType     = $this->userTypeService->getUserType(1);
        $colleagueType = $this->userTypeService->getUserType(3);
        $users         = $this->userService->getUsers($user, $adminType, $colleagueType);

        return Mapper::fromUsers(...$users);
    }

    /**
     * @param int $userId
     *
     * @return array
     * @throws NotAuthorizedException
     */
    private function getColleagues(int $userId)
    {
        $user     = $this->userService->getUser($userId);
        $userType = $this->userTypeService->getUserType(3);
        $agentIds = $this->agentService->getAgentIdsFromGroup((int)$user->getAgent()->getId());
        $users    = $this->userService->getColleagues($agentIds, $userType);

        return Mapper::fromUsers(...$users);
    }

    /**
     * @param int   $userId
     * @param array $parameters
     *
     * @return array $user
     *
     * @throws NotAuthorizedException
     * @throws UserAlreadyExistException
     */
    private function createUser(int $userId, array $parameters)
    {
        $user = $this->userService->getUser($userId);

        if ((int)$user->getUserType()->getId() > self::USER_AGENT) {
            throw new NotAuthorizedException($userId);
        }

        if (!array_key_exists('email', $parameters) && $parameters['email'] !== null) {
            throw new InvalidArgumentException("email parameter not provided");
        }
        if (!filter_var($parameters['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("email parameter not valid");
        }
        if (!array_key_exists('first_name', $parameters) && $parameters['first_name'] !== null) {
            throw new InvalidArgumentException("first_name parameter not provided");
        }
        if (!array_key_exists('last_name', $parameters) && $parameters['last_name'] !== null) {
            throw new InvalidArgumentException("last_name parameter not provided");
        }
        if (!array_key_exists('street', $parameters) && $parameters['street'] !== null) {
            throw new InvalidArgumentException("street parameter not provided");
        }
        if (!array_key_exists('house_number', $parameters) && $parameters['house_number'] !== null) {
            throw new InvalidArgumentException("house_number parameter not provided");
        }
        if (!array_key_exists('postcode', $parameters) && $parameters['postcode'] !== null) {
            throw new InvalidArgumentException("postcode parameter not provided");
        }
        if (!array_key_exists('city', $parameters) && $parameters['city'] !== null) {
            throw new InvalidArgumentException("city parameter not provided");
        }
        if (!array_key_exists('country', $parameters) && $parameters['country'] !== null) {
            throw new InvalidArgumentException("country parameter not provided");
        }
        if (!array_key_exists('agent_id', $parameters) && $parameters['agent_id'] !== null) {
            throw new InvalidArgumentException("agent_id parameter not provided");
        }
        if (!array_key_exists('user_type_id', $parameters) && $parameters['user_type_id'] !== null) {
            throw new InvalidArgumentException("user_type_id parameter not provided");
        }

        if ($this->userService->getUserByEmail($parameters['email'])) {
            throw new UserAlreadyExistException($parameters['email']);
        }

        $userType    = $this->userTypeService->getUserType($parameters['user_type_id']);
        $createdUser = $this->userService->createUser($parameters, $user->getAgent(), $userType);
        $password    = $this->randomPassword();
        $subject     = 'Invitation to create an account';

        $message = Swift_Message::newInstance()
                                ->setSubject($subject)
                                ->setFrom([self::EMAIL_FROM_EMAIL => self::EMAIL_FROM_NAME])
                                ->setTo($createdUser->getEmail())
                                ->setBody(
                                    $this->renderView(
                                        'AuthenticationBundle:Emails:Registration.html.twig',
                                        [
                                            'name'     => $parameters['first_name'],
                                            'password' => $password,
                                        ]
                                    ),
                                    'text/html'
                                )
                                ->addPart(
                                    $this->renderView(
                                        'AuthenticationBundle:Emails:Registration.txt.twig',
                                        [
                                            'name'     => $parameters['first_name'],
                                            'password' => $password,
                                        ]
                                    ),
                                    'text/plain'
                                );

        if ($this->get('mailer')->send($message)) {
            $this->mailService->createMail($user, $user->getAgent(), $createdUser->getEmail(), $subject);
        }

        $createdUser->setPassword(md5($password));
        $createdUser->setActive(true);
        $this->userService->updateUser($createdUser);

        return Mapper::fromUser($createdUser);
    }

    /**
     * @param int   $userId
     * @param array $parameters
     *
     * @return array
     *
     * @throws NotAuthorizedException
     */
    private function updateUser(int $userId, array $parameters)
    {
        if (!array_key_exists('id', $parameters) || empty($parameters['id'])) {
            throw new InvalidArgumentException("Identifier not provided");
        }

        $id   = (int)$parameters['id'];
        $user = $this->userService->getUser($userId);

        $updateUser = $this->userService->getUser($id);

        if ($updateUser->getAgent()->getId() !== $user->getAgent()->getId()) {
            throw new NotAuthorizedException($userId);
        }

        if (array_key_exists('first_name', $parameters) && $parameters['first_name'] !== null) {
            $updateUser->setFirstName(ucfirst($parameters['first_name']));
        }

        if (array_key_exists('last_name', $parameters) && $parameters['last_name'] !== null) {
            $updateUser->setLastName(ucfirst($parameters['last_name']));
        }

        if (array_key_exists('street', $parameters) && $parameters['street'] !== null) {
            $updateUser->setStreet(ucwords($parameters['street']));
        }

        if (array_key_exists('house_number', $parameters) && $parameters['house_number'] !== null) {
            $updateUser->setHouseNumber($parameters['house_number']);
        }

        if (array_key_exists('postcode', $parameters) && $parameters['postcode'] !== null) {
            $updateUser->setPostcode($parameters['postcode']);
        }

        if (array_key_exists('city', $parameters) && $parameters['city'] !== null) {
            $updateUser->setCity(ucwords($parameters['city']));
        }

        if (array_key_exists('country', $parameters) && $parameters['country'] !== null) {
            $updateUser->setCountry($parameters['country']);
        }

        if (array_key_exists('active', $parameters) && $parameters['active'] !== null) {
            $updateUser->setActive((bool)$parameters['active']);
        }

        if (array_key_exists('phone', $parameters) && $parameters['phone'] !== null) {
            $updateUser->setPhone((string)$parameters['phone']);
        }

        if (array_key_exists('avatar', $parameters) && $parameters['avatar'] !== null) {
            $updateUser->setAvatar((string)$parameters['avatar']);
        }


        return Mapper::fromUser($this->userService->updateUser($updateUser));
    }

    /**
     * @param int   $userId
     * @param array $parameters
     *
     * @throws NotAuthorizedException
     */
    private function setPassword(int $userId, array $parameters)
    {
        if (!array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException("No argument provided");
        }
        if (!array_key_exists('password', $parameters) && $parameters['password'] !== null) {
            throw new InvalidArgumentException("password parameter not provided");
        }

        $id         = (int)$parameters['id'];
        $user       = $this->userService->getUser($userId);
        $updateUser = $this->userService->getUser($id);

        if ($updateUser->getId() !== $user->getId()) {
            throw new NotAuthorizedException($userId);
        }

        $updateUser->setPassword(md5((string)$parameters['password']));
        $updateUser->setActive(true);

        $this->userService->updateUser($updateUser);
    }

    /**
     * @param int   $userId
     * @param array $parameters
     *
     * @throws NotAuthorizedException
     */
    private function disableUser(int $userId, array $parameters)
    {
        if (!array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException("No argument provided");
        }

        $id         = (int)$parameters['id'];
        $user       = $this->userService->getUser($userId);
        $updateUser = $this->userService->getUser($id);

        if ($updateUser->getAgent()->getId() !== $user->getAgent()->getId()) {
            throw new NotAuthorizedException($userId);
        }

        $this->userService->disableUser($id);
    }

    /**
     * @param int   $userId
     * @param array $parameters
     *
     * @throws NotAuthorizedException
     */
    private function deleteUser(int $userId, array $parameters)
    {
        if (!array_key_exists('id', $parameters)) {
            throw new InvalidArgumentException("No argument provided");
        }

        $id   = (int)$parameters['id'];
        $user = $this->userService->getUser($userId);

        if ((int)$user->getUserType()->getId() > self::USER_AGENT) {
            throw new NotAuthorizedException($userId);
        }

        $this->userService->deleteUser($id);
    }
}