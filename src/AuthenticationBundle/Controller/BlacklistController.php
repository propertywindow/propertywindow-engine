<?php
declare(strict_types = 1);

namespace AuthenticationBundle\Controller;

use AgentBundle\Exceptions\AgentNotFoundException;
use AppBundle\Controller\JsonController;
use AuthenticationBundle\Exceptions\BlacklistNotFoundException;
use AuthenticationBundle\Exceptions\NotAuthorizedException;
use AuthenticationBundle\Exceptions\UserNotFoundException;
use AuthenticationBundle\Service\Blacklist\Mapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Models\JsonRpc\Response;
use AppBundle\Exceptions\JsonRpc\InvalidJsonRpcMethodException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Throwable;

/**
 * @Route(service="AuthenticationBundle\Controller\BlacklistController")
 */
class BlacklistController extends JsonController
{
    /**
     * @Route("/authentication/blacklist" , name="blacklist")
     * @param Request $httpRequest
     *
     * @return HttpResponse
     * @throws Throwable
     */
    public function requestHandler(Request $httpRequest)
    {
        try {
            $method          = $this->prepareRequest($httpRequest);
            $jsonRpcResponse = Response::success($this->invoke($method));
        } catch (Throwable $throwable) {
            $jsonRpcResponse = $this->throwable($throwable, $httpRequest);
        }

        return $this->createResponse($jsonRpcResponse);
    }

    /**
     * @param string $method
     *
     * @return array
     * @throws InvalidJsonRpcMethodException
     */
    private function invoke(string $method)
    {
        if (is_callable([$this, $method])) {
            return $this->$method();
        }

        throw new InvalidJsonRpcMethodException("Method $method does not exist");
    }


    /**
     * @return array
     * @throws BlacklistNotFoundException
     * @throws NotAuthorizedException
     */
    private function getBlacklist(): array
    {
        $this->checkParameters(['id']);

        $blacklist = $this->blacklistService->getBlacklist((int)$this->parameters['id']);

        if ($this->user->getAgent()->getId() !== $blacklist->getAgent()->getId() ||
            $this->user->getUserType()->getId() !== self::USER_ADMIN
        ) {
            throw new NotAuthorizedException();
        }

        return Mapper::fromBlacklist($blacklist);
    }

    /**
     * @return array
     * @throws AgentNotFoundException
     * @throws NotAuthorizedException
     */
    private function getBlacklists(): array
    {
        $this->hasAccessLevel(self::USER_AGENT);

        $agent     = $this->agentService->getAgent($this->user->getAgent()->getId());
        $blacklist = $this->blacklistService->getBlacklists($agent);

        return Mapper::fromBlacklists(...$blacklist);
    }

    /**
     * @return array
     * @throws UserNotFoundException
     */
    private function createBlacklist(): array
    {
        $this->checkParameters([
            'user_id',
            'ip',
        ]);

        $user = $this->userService->getUser($this->parameters['user_id']);

        return Mapper::fromBlacklist($this->blacklistService->createBlacklist($this->parameters['ip'], $user));
    }

    /**
     * @throws BlacklistNotFoundException
     * @throws NotAuthorizedException
     */
    private function removeBlacklist()
    {
        $this->checkParameters(['id']);

        $this->hasAccessLevel(self::USER_AGENT);

        if (array_key_exists('id', $this->parameters)) {
            $this->blacklistService->removeBlacklist($this->parameters['id']);
        }
    }
}
