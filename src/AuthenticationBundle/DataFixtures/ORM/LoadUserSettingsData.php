<?php
declare(strict_types = 1);

namespace AuthenticationBundle\DataFixtures\ORM;

use AuthenticationBundle\Entity\User;
use AuthenticationBundle\Entity\UserSettings;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * LoadUserSettings Data
 */
class LoadUserSettingsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user */

        // Property Window

        for ($i = 1; $i <= 2; $i++) {
            $userSettings = new UserSettings();
            $user = $this->getReference('user_propertywindow_admin_' . $i);
            $userSettings->setUser($user);
            $userSettings->setLanguage('en');
            $userSettings->setEmailName('Property Window');
            $userSettings->setEmailAddress('propertywindownl@gmail.com');
            $userSettings->setIMAPAddress('imap.gmail.com');
            $userSettings->setIMAPPort(993);
            $userSettings->setIMAPSecure('SSL');
            $userSettings->setIMAPUsername('propertywindownl');
            $userSettings->setIMAPPassword('PropertyWindow12');
            $userSettings->setSMTPAddress('smtp.gmail.com');
            $userSettings->setSMTPPort(465);
            $userSettings->setSMTPSecure('SSL');
            $userSettings->setSMTPUsername('propertywindownl');
            $userSettings->setSMTPPassword('PropertyWindow12');
            $manager->persist($userSettings);
        }

        $userSettings = new UserSettings();
        $user = $this->getReference('user_propertywindow_agent_1');
        $userSettings->setUser($user);
        $userSettings->setLanguage('en');
        $manager->persist($userSettings);

        // Annan Users

        $userSettings = new UserSettings();
        $user = $this->getReference('user_annan_agent_1');
        $userSettings->setUser($user);
        $userSettings->setLanguage('en');
        $manager->persist($userSettings);

        $userSettings = new UserSettings();
        $user = $this->getReference('user_annan_agent_2');
        $userSettings->setUser($user);
        $userSettings->setLanguage('en');
        $manager->persist($userSettings);

        for ($i = 1; $i <= 6; $i++) {
            $userSettings = new UserSettings();
            $user = $this->getReference('user_annan_colleague_' . $i);
            $userSettings->setUser($user);
            $userSettings->setLanguage('en');
            $manager->persist($userSettings);
        }

        // Oliver Users

        $userSettings = new UserSettings();
        $user = $this->getReference('user_oliver_agent_1');
        $userSettings->setUser($user);
        $userSettings->setLanguage('en');
        $manager->persist($userSettings);

        // Deans Users

        for ($i = 1; $i <= 3; $i++) {
            $userSettings = new UserSettings();
            $user = $this->getReference('user_deans_agent_' . $i);
            $userSettings->setUser($user);
            $userSettings->setLanguage('en');
            $manager->persist($userSettings);
        }

        // Api

        $userSettings = new UserSettings();
        $user = $this->getReference('user_annan_api_1');
        $userSettings->setUser($user);
        $userSettings->setLanguage('en');
        $manager->persist($userSettings);

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 18;
    }
}
