<?php
declare(strict_types = 1);

namespace ConversationBundle\DataFixtures\ORM;

use ConversationBundle\Entity\EmailTemplateCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadEmailTemplateCategory Data
 */
class LoadEmailTemplateCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $emailTemplateCategory = new EmailTemplateCategory();
        $emailTemplateCategory->setEn('Users');
        $emailTemplateCategory->setNl('Gebruikers');
        $emailTemplateCategory->setActive(true);
        $this->setReference('email_template_category_user', $emailTemplateCategory);
        $manager->persist($emailTemplateCategory);

        $emailTemplateCategory = new EmailTemplateCategory();
        $emailTemplateCategory->setEn('Offer');
        $emailTemplateCategory->setNl('Biedingen');
        $emailTemplateCategory->setActive(true);
        $this->setReference('email_template_category_offer', $emailTemplateCategory);
        $manager->persist($emailTemplateCategory);

        $emailTemplateCategory = new EmailTemplateCategory();
        $emailTemplateCategory->setEn('Viewing');
        $emailTemplateCategory->setNl('Bezichtigingen');
        $emailTemplateCategory->setActive(true);
        $this->setReference('email_template_category_viewing', $emailTemplateCategory);
        $manager->persist($emailTemplateCategory);

        $emailTemplateCategory = new EmailTemplateCategory();
        $emailTemplateCategory->setEn('Appointment');
        $emailTemplateCategory->setNl('Afspreken');
        $emailTemplateCategory->setActive(true);
        $this->setReference('email_template_category_appointment', $emailTemplateCategory);
        $manager->persist($emailTemplateCategory);

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 40;
    }
}
