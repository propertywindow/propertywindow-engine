<?php declare(strict_types=1);

namespace ConversationBundle\DataFixtures\ORM;

use ConversationBundle\Entity\EmailTemplate;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadEmailTemplateData
 * @package ConversationBundle\DataFixtures\ORM
 */
class LoadEmailTemplateData  extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->setAgent($this->getReference('agent_propertywindow_1'));
        $emailTemplate->setCategory($this->getReference('email_template_category_user'));
        $emailTemplate->setName('invite_user');
        $emailTemplate->setActive(true);
        $emailTemplate->setSubject('Invitation to create an account');
        $emailTemplate->setMessageHTML('<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
    <style>
        * {
            font-family: sans-serif !important;
        }
    </style>
    <![endif]-->

    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href=\'https://fonts.googleapis.com/css?family=Roboto:400,700\' rel=\'stylesheet\' type=\'text/css\'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset -->
    <style>

        /* Remove spaces around the email design added by some email clients. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }

        /* Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* Fixes webkit padding issue.  */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],	/* iOS */
        .x-gmail-data-detectors, 	/* Gmail */
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn\'t work, add a .g-img class to any image in question. */
        img.g-img + div {
            display:none !important;
        }

        /* Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* Removes right gutter in Gmail iOS app  */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

    </style>

    <!-- Progressive Enhancements -->
    <style>

        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #5191CD !important;
            border-color: #5191CD !important;
        }

        /* Media Queries */
        @media screen and (max-width: 600px) {

            /* What it does: Adjust typography on small screens to improve readability */
            .email-container p {
                font-size: 17px !important;
                line-height: 22px !important;
            }

        }

    </style>

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<body width="100%" bgcolor="#EEEEEE" style="margin: 0; mso-line-height-rule: exactly;">
<center style="width: 100%; background: #EEEEEE; text-align: left;">

    <!-- Visually Hidden Preheader Text : BEGIN -->
    <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">
        (Optional) This text will appear in the inbox preview, but not the email body. It can be used to supplement the email subject line or even summarize the email\'s contents. Extended text preheaders (~490 characters) seems like a better UX for anyone using a screenreader or voice-command apps like Siri to dictate the contents of an email. If this text is not included, email clients will automatically populate it using the text (including image alt text) at the start of the email\'s body.
    </div>
    <!-- Visually Hidden Preheader Text : END -->

    <!--
        Set the email width. Defined in two places:
        1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
        2. MSO tags for Desktop Windows Outlook enforce a 600px width.
    -->
    <div style="max-width: 600px; margin: auto;" class="email-container">
        <!--[if mso]>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
            <tr>
                <td>
        <![endif]-->

        <!-- Email Header : BEGIN -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
            <tr>
                <td style="padding: 20px 0; text-align: center">
                    <img src="http://www.propertywindow.com/assets/images/pw-logo-windows.png" width="200" height="50" alt="alt_text" border="0" style="height: auto; background: #EEEEEE; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                </td>
            </tr>
        </table>
        <!-- Email Header : END -->

        <!-- Email Body : BEGIN -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">

            <!-- Header Image : BEGIN -->
            <tr>
                <td bgcolor="#ffffff" align="center">
                    <img src="http://placehold.it/1200x600" width="600" height="" alt="alt_text" border="0" align="center" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555; margin: auto;" class="g-img">
                </td>
            </tr>
            <!-- Header Image : END -->

            <!-- 1 Column Text + Button : BEGIN -->
            <tr>
                <td bgcolor="#ffffff">
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 27px; color: #333333; font-weight: normal;">Invitation to create an account.</h1>
                                <p style="margin: 0;">Hi {{ name }}! You\'re successfully registered.</p>
                                <p style="margin: 0;">You can login with the password: <b>{{ password }}</b></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 40px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                <!-- Button : BEGIN -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto;">
                                    <tr>
                                        <td style="border-radius: 3px; background: #222222; text-align: center;" class="button-td">
                                            <a href="https://www.propertywindow.com" style="background: #005480; border: 15px solid #005480; font-family: sans-serif; font-size: 13px; line-height: 1.1; text-align: center; text-decoration: none; display: block; border-radius: 3px; font-weight: bold;" class="button-a">
                                                <span style="color:#ffffff;" class="button-link">&nbsp;&nbsp;&nbsp;&nbsp;Create Account&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Button : END -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- 1 Column Text + Button : END -->

            <!-- 2 Even Columns : BEGIN -->
            <tr>
                <td bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%" style="padding-bottom: 40px">
                    &nbsp;
                </td>
            </tr>
            <!-- Two Even Columns : END -->

            <!-- Clear Spacer : BEGIN -->
            <tr>
                <td aria-hidden="true" height="40" style="font-size: 0; line-height: 0;">
                    &nbsp;
                </td>
            </tr>
            <!-- Clear Spacer : END -->


        </table>
        <!-- Email Body : END -->

        <!-- Email Footer : BEGIN -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
            <tr>
                <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; line-height:18px; text-align: center; color: #888888;" class="x-gmail-data-detectors">
                    &nbsp;
                </td>
            </tr>
        </table>
        <!-- Email Footer : END -->

        <!--[if mso]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </div>

    <!-- Full Bleed Background Section : BEGIN -->
    <table role="presentation" bgcolor="#78A22F" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
        <tr>
            <td valign="top" align="center">
                <div style="max-width: 600px; margin: auto;" class="email-container">
                    <!--[if mso]>
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                        <tr>
                            <td>
                    <![endif]-->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td style="padding: 40px; text-align: center; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff;">
                                <p style="margin: 0;">Property Window<br>27 Portobello High Street, EH15 1DE, Edinburgh, GB<br>0131 657 1666</p>
                            </td>
                        </tr>
                    </table>
                    <!--[if mso]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->
                </div>
            </td>
        </tr>
    </table>
    <!-- Full Bleed Background Section : END -->

</center>
</body>
</html>');
        $emailTemplate->setMessageTXT('You did it! You registered!

Hi {{ name }}! You\'re successfully registered.
You can login with the password: {{ password }}

Thanks!');
        $manager->persist($emailTemplate);

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 41;
    }
}
