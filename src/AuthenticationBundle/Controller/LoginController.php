<?php
declare(strict_types=1);

namespace AuthenticationBundle\Controller;

use AppBundle\Controller\JsonController;
use AuthenticationBundle\Exceptions\BlacklistNotFoundException;
use AuthenticationBundle\Exceptions\NotAuthorizedException;
use AuthenticationBundle\Exceptions\UserNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Models\JsonRpc\Response;
use AppBundle\Exceptions\JsonRpc\InvalidJsonRpcMethodException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

/**
 * @Route(service="AuthenticationBundle\Controller\LoginController")
 */
class LoginController extends JsonController
{
    /**
     * @Route("/authentication/login" , name="login")
     * @param Request $httpRequest
     *
     * @return HttpResponse
     * @throws Throwable
     */
    public function requestHandler(Request $httpRequest)
    {
        try {
            $method          = $this->prepareRequest($httpRequest, false);
            $jsonRpcResponse = Response::success($this->invoke($method, $httpRequest->getClientIp()));
        } catch (Throwable $throwable) {
            $jsonRpcResponse = $this->throwable($throwable, $httpRequest);
        }

        return $this->createResponse($jsonRpcResponse);
    }

    /**
     * @param string $method
     * @param string $ipAddress
     *
     * @return array
     * @throws BlacklistNotFoundException
     * @throws InvalidJsonRpcMethodException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     */
    private function invoke(string $method, string $ipAddress)
    {
        switch ($method) {
            case "login":
                return $this->login($ipAddress);
            case "impersonate":
                return $this->impersonate();
        }

        throw new InvalidJsonRpcMethodException("Method $method does not exist");
    }

    /**
     * @param string $ipAddress
     *
     * @return array
     * @throws BlacklistNotFoundException
     */
    private function login(string $ipAddress): array
    {
        $this->checkParameters([
            'email',
            'password',
        ]);

        $email    = (string)$this->parameters['email'];
        $password = md5((string)$this->parameters['password']);
        $user     = $this->userService->login($email, $password);

        if ($user === null) {
            $attemptedUser = $this->userService->getUserByEmail($email);

            $this->blacklistService->createBlacklist($ipAddress, $attemptedUser);

            return json_encode([
                'error' => [
                    'message' => 'Could not authenticate user.',
                    'code' => self::USER_NOT_AUTHENTICATED,
                ],
            ]);
        }

        $blacklist = $this->blacklistService->checkBlacklist($ipAddress);

        if ($blacklist) {
            $this->blacklistService->removeBlacklist($blacklist->getId());
        }

        $this->logLoginService->createLogin(
            $user,
            $ipAddress
        );

        $timestamp      = time();
        $secret         = $user->getPassword();
        $signature      = hash_hmac("sha1", $timestamp . "-" . $user->getId(), $secret);
        $payload        = [
            "user"      => $user->getId(),
            "password"  => $secret,
            "timestamp" => $timestamp,
            "signature" => $signature,
        ];
        $payloadJson    = json_encode($payload);
        $payloadEncoded = base64_encode($payloadJson);

        return [
            'user_id' => $user->getId(),
            'token'   => $payloadEncoded,
        ];
    }


    /**
     * @return array
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     */
    private function impersonate(): array
    {
        $this->checkParameters([
            'user_id',
            'impersonate_id',
        ]);

        $user        = $this->userService->getUser((int)$this->parameters['user_id']);
        $impersonate = $this->userService->getUser((int)$this->parameters['impersonate_id']);

        if ((int)$user->getUserType()->getId() > self::USER_AGENT) {
            throw new NotAuthorizedException();
        }

        if ((int)$user->getUserType()->getId() !== self::USER_ADMIN) {
            if ($user->getAgent() !== $impersonate->getAgent()) {
                throw new NotAuthorizedException();
            }
        }

        $timestamp      = time();
        $secret         = $impersonate->getPassword();
        $signature      = hash_hmac("sha1", $timestamp . "-" . $impersonate->getId(), $secret);
        $payload        = [
            "user"      => $impersonate->getId(),
            "password"  => $secret,
            "timestamp" => $timestamp,
            "signature" => $signature,
        ];
        $payloadJson    = json_encode($payload);
        $payloadEncoded = base64_encode($payloadJson);

        return [
            'user_id' => $impersonate->getId(),
            'email'   => $impersonate->getEmail(),
            'token'   => $payloadEncoded,
        ];
    }
}
