<?php declare(strict_types=1);

namespace PropertyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PropertyBundle\Entity\Terms;

/**
 * Class LoadTermsData
 * @package PropertyBundle\DataFixtures\ORM
 */
class LoadTermsData implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $terms = new Terms();
        $terms->setEn('New');
        $terms->setNl('Nieuw');
        $terms->setShowPrice(false);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Coming soon');
        $terms->setNl('Binnenkort beschikbaar');
        $terms->setShowPrice(false);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Fixed price');
        $terms->setNl('Vaste prijs');
        $terms->setShowPrice(true);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Offers around');
        $terms->setNl('Bod rond');
        $terms->setShowPrice(true);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Offers over');
        $terms->setNl('Bod over');
        $terms->setShowPrice(true);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('New price');
        $terms->setNl('Prijswijziging');
        $terms->setShowPrice(true);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Under offer');
        $terms->setNl('Verkocht onder voorbehoud');
        $terms->setShowPrice(false);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Retracted');
        $terms->setNl('Ingetrokken');
        $terms->setShowPrice(false);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Sold');
        $terms->setNl('Verkocht');
        $terms->setShowPrice(false);
        $manager->persist($terms);

        $terms = new Terms();
        $terms->setEn('Rented');
        $terms->setNl('Verhuurd');
        $terms->setShowPrice(false);
        $manager->persist($terms);

        $manager->flush();
    }
}
