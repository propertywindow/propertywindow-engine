<?php
declare(strict_types = 1);

namespace Tests\AgentBundle\Entity;

use AgentBundle\Entity\Agent;
use AgentBundle\Entity\AgentGroup;
use AppBundle\Entity\ContactAddress;
use AuthenticationBundle\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 *  Agent Test
 */
class AgentTest extends TestCase
{
    /**
     * @var Agent
     */
    private $agent;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->agent = new Agent();
    }

    public function testAddress()
    {
        $address = new ContactAddress();

        $this->agent->setAddress($address);
        $this->assertEquals($address, $this->agent->getAddress());
    }

    public function testGetterAndSetter()
    {
        $this->assertNull($this->agent->getId());

        $agentGroup = new AgentGroup();

        $this->agent->setAgentGroup($agentGroup);
        $this->assertEquals($agentGroup, $this->agent->getAgentGroup());

        $this->agent->setOffice('Edinburgh');
        $this->assertEquals('Edinburgh', $this->agent->getOffice());


        $this->agent->setEmail('info@propertywindow.com');
        $this->assertEquals('info@propertywindow.com', $this->agent->getEmail());

        $this->agent->setPhone('01316571666');
        $this->assertEquals('01316571666', $this->agent->getPhone());

        $this->agent->setFax('');
        $this->assertEmpty($this->agent->getFax());

        $this->agent->setWebsite('https://www.propertywindow.nl');
        $this->assertEquals('https://www.propertywindow.nl', $this->agent->getWebsite());

        $this->agent->setLogo('');
        $this->assertEmpty($this->agent->getLogo());

        $this->agent->setPropertyLimit(200);
        $this->assertEquals(200, $this->agent->getPropertyLimit());

        $this->agent->setEspc(true);
        $this->assertTrue($this->agent->isEspc());

        $this->agent->setWebprint(false);
        $this->assertFalse($this->agent->getWebprint());

        $this->agent->setArchived(false);
        $this->assertFalse($this->agent->isArchived());

        $user = new User();

        $this->agent->setUser($user);
        $this->assertEquals($user, $this->agent->getUser());

        $created = new \DateTime();

        $this->agent->setCreated($created);
        $this->assertEquals($created, $this->agent->getCreated());

        $this->agent->setUpdated(null);
        $this->assertNull($this->agent->getUpdated());
    }
}
