<?php
declare(strict_types = 1);

namespace AgentBundle\DataFixtures\ORM;

use AgentBundle\Entity\Agency;
use AgentBundle\Entity\Agent;
use AppBundle\Entity\ContactAddress;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadAgencyData
 */
class LoadAgencyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Agent $agent */
        /** @var ContactAddress $address */

        $agency = new Agency();
        $agent  = $this->getReference('agent_annan_1');
        $agency->setAgent($agent);
        $agency->setName('Aikman Bell');
        $address = $this->getReference('address_agent_annan_agency_1');
        $agency->setAddress($address);
        $agency->setEmail('info@aikmainbell.co.uk');
        $agency->setPhone('0131 661 0015');
        $this->setReference('agent_annan_agency_1', $agency);
        $manager->persist($agency);

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 13;
    }
}
