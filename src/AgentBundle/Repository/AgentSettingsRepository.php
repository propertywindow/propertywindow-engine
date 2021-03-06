<?php declare(strict_types = 1);

namespace AgentBundle\Repository;

use AgentBundle\Entity\Agent;
use AgentBundle\Entity\AgentSettings;
use AgentBundle\Exceptions\AgentSettingsNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * AgentSettingsRepository
 */
class AgentSettingsRepository extends EntityRepository
{
    /**
     * @param int $agentId
     *
     * @return AgentSettings
     * @throws AgentSettingsNotFoundException
     */
    public function findByAgentId(int $agentId): AgentSettings
    {
        $result = $this->find($agentId);

        if ($result === null) {
            throw new AgentSettingsNotFoundException($agentId);
        }

        /** @var AgentSettings $result */
        return $result;
    }

    /**
     * @param Agent $agent
     *
     * @return AgentSettings
     * @throws AgentSettingsNotFoundException
     */
    public function findByAgent(Agent $agent): AgentSettings
    {
        $result = $this->findOneBy([
            'agent' => $agent,
        ]);

        if ($result === null) {
            throw new AgentSettingsNotFoundException($agent->getId());
        }

        /** @var AgentSettings $result */
        return $result;
    }
}
