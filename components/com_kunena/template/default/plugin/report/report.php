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

// Dont allow direct linking
defined ('_VALID_MOS') or die('Direct Access to this location is not allowed.');

$msg_id = mosGetParam($_REQUEST, 'msg_id');
$catid = (int)mosGetParam($_REQUEST, 'catid', 0);
$reporter = mosGetParam($_REQUEST, 'reporter');
$reason = strval(mosGetParam($_REQUEST, 'reason'));
$text = strval(mosGetParam($_REQUEST, 'text'));
$type = mosGetParam($_REQUEST, 'type', 0); // 0 = send e-mail, 1 = send pm

switch ($do)
{
    case 'report':
        ReportMessage((int)$msg_id, $catid, (int)$reporter, $reason, $text, (int)$type);

        break;

    default:
        ReportForm($msg_id, $catid);

        break;
}

function ReportMessage($msg_id, $catid, $reporter, $reason, $text, $type) {
    global $database, $my;
    $fbConfig =& CKunenaConfig::getInstance();

    if (!$my->id) {
        mosNotAuth();
        return;
        }

	if (!empty($reason) && !empty($text))
	{
        
    $database->setQuery("SELECT a.*, b.message AS msg_text FROM #__fb_messages AS a"
    . "\n LEFT JOIN #__fb_messages_text AS b ON b.mesid = a.id"
    . "\n WHERE a.id={$msg_id}");

    $database->loadObject($row);

    $database->setQuery("SELECT username FROM #__users WHERE id={$row->userid}");
    $baduser = $database->loadResult();

    $database->setQuery("SELECT username FROM #__users WHERE id={$reporter}");
    $sender = $database->loadResult();

    if ($reason) {
        $subject = "[".stripslashes($fbConfig->board_title)." "._GEN_FORUM."] "._KUNENA_REPORT_MSG . ": " . $reason;
        }
    else {
        $subject = "[".stripslashes($fbConfig->board_title)." "._GEN_FORUM."] "._KUNENA_REPORT_MSG . ": " . stripslashes($row->subject);
        }

    $msglink = str_replace('&amp;', '&', sefRelToAbs(KUNENA_LIVEURLREL . "&amp;func=view&amp;catid=" . $row->catid . "&amp;id=" . $row->id) . '#' . $row->id);

    $message  = "" . _KUNENA_REPORT_RSENDER . " " . $sender;
    $message .= "\n";
    $message .= "" . _KUNENA_REPORT_RREASON . " " . $reason;
    $message .= "\n";
    $message .= "" . _KUNENA_REPORT_RMESSAGE . " " . $text;
    $message .= "\n\n";
    $message .= "" . _KUNENA_REPORT_POST_POSTER . " " . $baduser;
    $message .= "\n";
    $message .= "" . _KUNENA_REPORT_POST_SUBJECT . " " . stripslashes($row->subject);
    $message .= "\n";
    $message .= "" . _KUNENA_REPORT_POST_MESSAGE . "\n-----\n" . stripslashes($row->msg_text);
    $message .= "\n-----\n\n";
    $message .= "" . _KUNENA_REPORT_POST_LINK . " " . $msglink;
    $message .= "\n\n\n\n** Powered by Kunena! - http://www.Kunena.com **";
    $message = strtr($message, array('&#32;'=>''));

    //get category moderators
    $database->setQuery("SELECT userid FROM #__fb_moderation WHERE catid={$row->catid}");
    $mods = $database->loadObjectList();
    	check_dberror("Unable to load moderators.");

    //get admins
    $database->setQuery("SELECT id FROM #__users WHERE gid IN (24, 25)");
    $admins = $database->loadObjectList();
    	check_dberror("Unable to load admin.");

    switch ($type)
    {
        default:
        case '0':
            SendReporttoMail($sender, $subject, $message, $msglink, $mods, $admins);

            break;

        case '1':
            SendReporttoPM($reporter, $subject, $message, $msglink, $mods, $admins);

            break;
    }
    
    echo '<div align="center">' . _KUNENA_REPORT_SUCCESS;
    echo CKunenaLink::GetAutoRedirectHTML(sefRelToAbs(KUNENA_LIVEURLREL.'&amp;func=view&amp;catid='.$catid.'&amp;id='.$msg_id).'#'.$msg_id, 3500);
    
	}
    else
    {
    	echo '<div align="center">';
    	if (empty($reason)) echo _POST_FORGOT_SUBJECT; 
    	else if (empty($text)) echo _POST_FORGOT_MESSAGE;
    	
    }
    echo '<br /><br />';
    echo '<a href="' . sefRelToAbs(KUNENA_LIVEURLREL . '&amp;func=view&amp;catid=' . $catid . '&amp;id=' . $msg_id) . '#' . $msg_id . '">' . _POST_SUCCESS_VIEW . '</a><br />';
    echo '<a href="' . sefRelToAbs(KUNENA_LIVEURLREL . '&amp;func=showcat&amp;catid=' . $catid) . '">' . _POST_SUCCESS_FORUM . '</a><br />';
    echo '</div>';
}

function SendReporttoMail($sender, $subject, $message, $msglink, $mods, $admins) {
    global $database, $mainframe;

    $fbConfig =& CKunenaConfig::getInstance();

    //send report to category moderators
    if (count($mods)>0) {
        foreach ($mods as $mod) {
            $database->setQuery("SELECT email FROM #__users WHERE id={$mod->userid}");
            $email = $database->loadResult();

            mosMail($fbConfig->email, $fbConfig->board_title, $email, $subject, $message);
            }
    }

    //send report to site admins
    foreach ($admins as $admin) {
        $database->setQuery("SELECT email FROM #__users WHERE id={$admin->id}");
        $email = $database->loadResult();
        mosMail($fbConfig->email, stripslashes($fbConfig->board_title)." ".trim(_GEN_FORUM), $email, $subject, $message);
        }
    }

function SendReporttoPM($sender, $subject, $message, $msglink, $mods, $admins) {
    global $database;

    $fbConfig =& CKunenaConfig::getInstance();

    switch ($fbConfig->pm_component)
    {
        case 'no': break;

        //myPMS II Open Source
        case 'pms':
            SendPMS();

            break;

        //Clexus PM
        case 'clexuspm':
            SendClexusPM($reporter, $subject, $message, $msglink, $mods, $admins);

            break;

        //uddeIM
        case 'uddeim':
            SendUddeIM();

            break;

        //JIM
        case 'jim':
            SendJIM();

            break;

        case 'missus':
            SendMissus();

            break;
    }
    }

function ReportForm($msg_id, $catid) {
    global $my;

    $fbConfig =& CKunenaConfig::getInstance();

    $redirect = sefRelToAbs(KUNENA_LIVEURLREL . '&amp;func=view&amp;catid=' . $catid . '&amp;id=' . $msg_id . '&amp;Itemid=' . KUNENA_COMPONENT_ITEMID) . '#' . $msg_id;

    //$redirect = sefRelToAbs($redirect);
    if (!$my->id) {
        mosRedirect ($redirect);
        return;
        }

    if ($fbConfig->reportmsg == 0) {
        mosRedirect ($redirect);
        return;
        }
?>

<div class = "<?php echo $boardclass; ?>_bt_cvr1">
    <div class = "<?php echo $boardclass; ?>_bt_cvr2">
        <div class = "<?php echo $boardclass; ?>_bt_cvr3">
            <div class = "<?php echo $boardclass; ?>_bt_cvr4">
                <div class = "<?php echo $boardclass; ?>_bt_cvr5">
                    <table class = "fb_blocktable" id = "fb_forumfaq" border = "0" cellspacing = "0" cellpadding = "0" width = "100%">
                        <thead>
                            <tr>
                                <th>
                                    <div class = "fb_title_cover">
                                        <span class = "fb_title"><?php echo _KUNENA_COM_A_REPORT ?></span>
                                    </div>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class = "fb_faqdesc">
                                    <form method = "post" action = "<?php echo sefRelToAbs(KUNENA_LIVEURLREL.'&amp;func=report'); ?>">
                                        <table width = "100%" border = "0">
                                            <tr>
                                                <td width = "10%">
<?php echo _KUNENA_REPORT_REASON ?>:
                                                </td>

                                                <td>
                                                    <input type = "text" name = "reason" class = "inputbox" size = "30"/>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan = "2">
<?php echo _KUNENA_REPORT_MESSAGE ?>:
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan = "2">
                                                    <textarea id = "text" name = "text" cols = "40" rows = "10" class = "inputbox"></textarea>
                                                </td>
                                            </tr>
                                        </table>

                                        <input type = "hidden" name = "do" value = "report"/>
                                        <input type = "hidden" name = "msg_id" value = "<?php echo $msg_id;?>"/>
                                        <input type = "hidden" name = "catid" value = "<?php echo $catid;?>"/>
                                        <input type = "hidden" name = "reporter" value = "<?php echo $my->id;?>"/>
                                        <input type = "submit" name = "Submit" value = "<?php echo _KUNENA_REPORT_SEND ?>"/>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    }

function SendClexusPM($reporter, $subject, $message, $msglink, $mods, $admins) {
    global $database;
    $time = mosFormatDate(CKunenaTools::fbGetInternalTime(), '%Y-%m-%d %H:%M:%S');

    foreach ($admins as $admin) {
        $database->setQuery("INSERT INTO #__mypms" . "\n ( `userid` , `whofrom` , `time` , `readstate` , `subject` , `message` , `owner` , `folder` , `sent_id` , `replyid` , `ip` , `alert` , `flag` , `pm_notify` , `email_notify` )"
                                . "\n VALUES ('$admin->id', '$reporter', '$time', '0', '$subject', '$message', '$admin->id', NULL , '0', '0', NULL , '0', '0', '0', '1'");
        $database->query();
        }

    foreach ($mods as $mod) {
        $database->setQuery("INSERT INTO #__mypms" . "\n ( `userid` , `whofrom` , `time` , `readstate` , `subject` , `message` , `owner` , `folder` , `sent_id` , `replyid` , `ip` , `alert` , `flag` , `pm_notify` , `email_notify` )"
                                . "\n VALUES ('$mod->id', '$reporter', '$time', '0', '$subject', '$message', '$mod->id', NULL , '0', '0', NULL , '0', '0', '0', '1'");
        $database->query();
        }

    mosErrorAlert('' . _KUNENA_REPORT_SUCCESS . '', 'window.history.go(-2);');
    }
?>
