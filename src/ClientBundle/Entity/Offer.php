<?php
declare(strict_types = 1);

namespace ClientBundle\Entity;

use AppBundle\Entity\Acceptance;
use Doctrine\ORM\Mapping as ORM;
use PropertyBundle\Entity\Property;

/**
 * @ORM\Table(name="offer")
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\OfferRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Offer
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Buyer")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     */
    private $buyer;

    /**
     * @ORM\OneToOne(targetEntity="PropertyBundle\Entity\Property")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Acceptance")
     * @ORM\JoinColumn(name="acceptance_id", referencedColumnName="id")
     */
    protected $acceptance;

    /**
     * @var int
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var \DateTime $entryDate
     * @ORM\Column(type="datetime")
     */
    protected $entryDate;

    /**
     * @var \DateTime $created
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $updated
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param Buyer $buyer
     *
     * @return Offer
     */
    public function setBuyer(Buyer $buyer): Offer
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * @return Buyer
     */
    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    /**
     * @param \PropertyBundle\Entity\Property $property
     *
     * @return Offer
     */
    public function setProperty(Property $property): Offer
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return \PropertyBundle\Entity\Property
     */
    public function getProperty(): Property
    {
        return $this->property;
    }

    /**
     * @param \AppBundle\Entity\Acceptance $acceptance
     *
     * @return Offer
     */
    public function setAcceptance(Acceptance $acceptance): Offer
    {
        $this->acceptance = $acceptance;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Acceptance
     */
    public function getAcceptance(): Acceptance
    {
        return $this->acceptance;
    }

    /**
     * @param integer $amount
     *
     * @return Offer
     */
    public function setAmount(int $amount): Offer
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param \DateTime|null $entryDate
     *
     * @return Offer
     */
    public function setEntryDate(?\DateTime $entryDate): Offer
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEntryDate(): \DateTime
    {
        return $this->entryDate;
    }

    /**
     * @param \DateTime|null $created
     *
     * @return Offer
     */
    public function setCreated(?\DateTime $created): Offer
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime|null $updated
     *
     * @return Offer
     */
    public function setUpdated(?\DateTime $updated): Offer
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }
}
