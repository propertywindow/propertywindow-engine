<?php declare(strict_types = 1);

namespace PropertyBundle\Repository;

use AgentBundle\Entity\Agent;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PropertyBundle\Entity\Property;
use PropertyBundle\Exceptions\PropertyNotFoundException;

/**
 * PropertyRepository
 */
class PropertyRepository extends EntityRepository
{
    /**
     * @param int $id
     *
     * @return Property
     * @throws PropertyNotFoundException
     */
    public function findById(int $id): Property
    {
        $result = $this->find($id);

        if ($result === null) {
            throw new PropertyNotFoundException($id);
        }

        /** @var Property $result */
        return $result;
    }

    /**
     * @param Agent $agent
     *
     * @return Property[]
     */
    public function listProperties(Agent $agent): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                   ->select('p')
                   ->from('PropertyBundle:Property', 'p')
                   ->where('p.agent = :agent')
                   ->orderBy('p.id', 'DESC')
                   ->setParameter('agent', $agent);

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * @param int[] $agentIds
     * @param int   $limit
     * @param int   $offset
     *
     * @return array First value Property[], second value the total count.
     */
    public function listAllProperties($agentIds, int $limit, int $offset): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                   ->select('p')
                   ->from('PropertyBundle:Property', 'p')
                   ->where("p.agent IN (:agentIds)")
                   ->orderBy('p.id', 'DESC')
                   ->setFirstResult($offset)
                   ->setMaxResults($limit)
                   ->setParameter('agentIds', $agentIds);

        $pages = new Paginator($qb);

        $count   = $pages->count();
        $results = $pages->getQuery()->getResult();

        return [
            $results,
            $count,
        ];
    }

    /**
     * @param int $subTypeId
     *
     * @return Property[]
     */
    public function findPropertiesWithSubType(int $subTypeId): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
                   ->select('p')
                   ->from('PropertyBundle:Property', 'p');

        $qb->where("p.subType = :subTypeId");
        $qb->andWhere("p.archived = false");
        $qb->orderBy('p.id', 'DESC');
        $qb->setParameter('subTypeId', $subTypeId);

        $results = $qb->getQuery()->getResult();

        return $results;
    }
}
