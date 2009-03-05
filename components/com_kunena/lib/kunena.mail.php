<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2008 - 2009 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
*
* Based on FireBoard Component
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*
* Based on Joomlaboard Component
* @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author TSMF & Jan de Graaff
**/

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

class fbMail
{
    /**
    * function to send mails (uses mosmail in mambo 4.5.1 and later, falls back to phpmail if mosmail doesn't exist
    *
    * @param string $fromMail
    * @param string $fromName
    * @param string $toMail
    * @param string $subject
    * @param string $body
    *             message to be send
    */
    function send($fromMail, $fromName, $toMail, $subject, $body)
    {
        if (function_exists(mosMail))
            mosMail($fromMail, $fromName, $toMail, $subject, $body);
        else
        {
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain;" . _ISO . "\r\n";
            $headers .= "From: $fromName <$fromMail>\r\n";
            $headers .= "Reply-To: $fromName <$fromMail>\r\n";
            $headers .= "X-Priority: 3\r\n";
            $headers .= "X-MSMail-Priority: Low\r\n";
            $headers .= "X-Mailer: Mambo Open Source 4.5\r\n";
            mail($toMail, $subject, $body, $headers);
        }
    }
}
?>