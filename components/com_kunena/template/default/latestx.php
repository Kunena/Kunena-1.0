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

$fbConfig =& CKunenaConfig::getInstance();
$fbSession =& CKunenaSession::getInstance();
//Start with determining which forums the user can see

require_once (KUNENA_ABSSOURCESPATH . 'kunena.authentication.php');
//resetting some things:
$lockedForum = 0;
$lockedTopic = 0;
$topicSticky = 0;

//start the latest x
if ($sel == "0") {
    $querytime = ($prevCheck - $fbConfig->fbsessiontimeout); //move 30 minutes back to compensate for expired sessions
}
else
{
    if ("" == $sel) {
        $sel = 720;
    } //take 720 hours ~ 1 month as default
    //Time translation
    $back_time = $sel * 3600; //hours*(mins*secs)
    $querytime = time() - $back_time;
}

// get all the threads with posts in the specified timeframe
$database->setQuery(
    "SELECT
        a.thread,
        a.subject,
        b.lastpost
     FROM
        #__fb_messages AS a
        JOIN (  SELECT thread, MAX(time) AS lastpost
                FROM #__fb_messages
                WHERE time >'$querytime' AND hold=0 AND moved=0 AND catid IN ($fbSession->allowed)
                GROUP BY 1) AS b ON b.thread = a.thread
     WHERE
        a.parent=0
        AND a.moved=0
        AND a.hold=0
     GROUP BY a.thread
     ORDER BY b.lastpost DESC LIMIT 100");
$resultSet = $database->loadObjectList();
	check_dberror("Unable to load messages.");
$countRS = count($resultSet);

//check if $sel has a reasonable value and not a Unix timestamp:
$since = false;

$lastvisit = '';
if ($sel == "0")
{
    $lastvisit = date(_DATETIME, $querytime);
    $since = true;
}
?>
<div class="<?php echo $boardclass; ?>_bt_cvr1">
<div class="<?php echo $boardclass; ?>_bt_cvr2">
<div class="<?php echo $boardclass; ?>_bt_cvr3">
<div class="<?php echo $boardclass; ?>_bt_cvr4">
<div class="<?php echo $boardclass; ?>_bt_cvr5">
<table class = "fb_blocktable" id ="fb_latestx"   border = "0" cellspacing = "0" cellpadding = "0" width="100%">
    <thead>
        <tr>
            <th colspan = "4">
                <div class = "fb_title_cover" style = "text-align:center; display:block; width:100%;">
                    <span class="fb_title">

                    <?php
                    if (!$since) {
                        echo _SHOW_LAST_POSTS . " $sel";
                    }
                    else {
                        echo _SHOW_LAST_SINCE;
                    }
                    ?>

                    <?php echo $lastvisit; ?> <?php echo _SHOW_HOURS; ?> (<?php echo _SHOW_POSTS; ?><?php echo $countRS; ?>)</span> <?php echo _DESCRIPTION_POSTS; ?><br/>

                    <?php echo CKunenaLink::GetShowLatestThreadsLink(4, _SHOW_4_HOURS) . ' | ';
                          echo CKunenaLink::GetShowLatestThreadsLink(8, _SHOW_8_HOURS) . ' | ';
                          echo CKunenaLink::GetShowLatestThreadsLink(12, _SHOW_12_HOURS) . ' | ';
                          echo CKunenaLink::GetShowLatestThreadsLink(24, _SHOW_24_HOURS) . ' | ';
                          echo CKunenaLink::GetShowLatestThreadsLink(48, _SHOW_48_HOURS) . ' | ';
                          echo CKunenaLink::GetShowLatestThreadsLink(168, _SHOW_WEEK) . ' | ';
                          echo CKunenaLink::GetShowLatestThreadsLink(0, _SHOW_LASTVISIT) ;
                    ?>

                </div>
			</th>
        </tr>
    </thead>

    <tbody id = "<?php echo $boardclass; ?>latestx_tbody">
        <tr class = "fb_sth fbs">
            <th class = "th-1 <?php echo $boardclass; ?>sectiontableheader" width="60%" align="left"><?php echo _LATEST_THREADFORUM; ?>
            </th>

            <th class = "th-2 <?php echo $boardclass; ?>sectiontableheader"  width="10%" align="center"><?php echo _LATEST_NUMBER; ?>
            </th>

            <th class = "th-3 <?php echo $boardclass; ?>sectiontableheader" width="15%" align="center"><?php echo _LATEST_AUTHOR; ?>
            </th>

            <th class = "th-4 <?php echo $boardclass; ?>sectiontableheader"  width="15%" align="left"><?php echo _POSTED_AT; ?>
            </th>
        </tr>

        <?php
        if (0 < $countRS)
        {
            $tabclass = array
            (
                "sectiontableentry1",
                "sectiontableentry2"
            );

            $k = 0; //for alternating rows

            foreach ($resultSet as $rs)
            {
                //get the latest post time for this thread
                unset($thisThread);
                $database->setQuery("SELECT max(time) AS maxtime, count(*) AS totalmessages FROM #__fb_messages where thread={$rs->thread}");
                $database->loadObject($thisThread);
                $latestPostTime = $thisThread->maxtime;

                //get the latest post itself
                unset($result);
                $database->setQuery("SELECT a.id,a.name,a.userid,a.catid,b.name as catname from #__fb_messages as a LEFT JOIN #__fb_categories as b on a.catid=b.id where a.time={$latestPostTime}");
                $database->loadObject($result);

                $latestPostId = $result->id;
                $latestPostName = html_entity_decode_utf8(stripslashes($result->name));
				$latestPostUserid = $result->userid;
                $latestPostCatid = $result->catid;
                $catname = kunena_htmlspecialchars(stripslashes($result->catname));
                $database->setQuery("SELECT count(*) from #__fb_messages where time>'{$querytime}' and thread={$rs->thread}");
                $numberOfPosts = $database->loadResult();
                $k = 1 - $k;
                echo '<tr  class="' . $boardclass . '' . $tabclass[$k] . '" >';
                echo '<td  class="td-1"  align="left" >';
                echo CKunenaLink::GetThreadLink('view', $latestPostCatid, $rs->thread, kunena_htmlspecialchars(stripslashes($rs->subject)), kunena_htmlspecialchars(stripslashes($rs->subject))).' ';

                $threadPages = 1;
                if ($thisThread->totalmessages > $fbConfig->messages_per_page)
                {
                    $threadPages = ceil($thisThread->totalmessages / $fbConfig->messages_per_page);
                    echo ("<span class=\"jr-showcat-perpage\">[");
                    echo _PAGE.' '.CKunenaLink::GetThreadPageLink($fbConfig, 'view', $latestPostCatid, $rs->thread, 1, $fbConfig->messages_per_page, 1);

                    if ($threadPages > 3)
                    {
                        echo ("...");
                        $startPage = $threadPages - 2;
                    }
                    else
                    {
                        echo (",");
                        $startPage = 2;
                    }

                    $noComma = true;

                    for ($hopPage = $startPage; $hopPage <= $threadPages; $hopPage++)
                    {
                        if ($noComma) {
                            $noComma = false;
                            }
                        else {
                            echo (",");
                            }

                        echo CKunenaLink::GetThreadPageLink($fbConfig, 'view', $latestPostCatid, $rs->thread, $hopPage, $fbConfig->messages_per_page, $hopPage);
                    }

                    echo ']</span> ';
                }

                $tmpicon = isset($fbIcons['latestpost']) ? '<img src="'
                     .KUNENA_URLICONSPATH.''.$fbIcons['latestpost'].'" border="0" alt="'._SHOW_LAST.'" title="'._SHOW_LAST.'" />':'  <img src="'.KUNENA_URLEMOTIONSPATH.'icon_newest_reply.gif" border="0"  alt="'._SHOW_LAST.'" title="'._SHOW_LAST.'" />';
                echo CKunenaLink::GetThreadPageLink($fbConfig, 'view', $latestPostCatid, $rs->thread, $threadPages, $fbConfig->messages_per_page, $tmpicon, $latestPostId);

                echo '<br />' . _GEN_FORUM . ' : ' . $catname . '</td>';
                echo '<td class="td-2" align="center">' . $numberOfPosts . '</td>';
                echo '<td class="td-3" align="center">';
                echo CKunenaLink::GetProfileLink($fbConfig, $latestPostUserid, kunena_htmlspecialchars($latestPostName));
                echo '</td>';
                echo '<td class="td-4" align="left">' . date(_DATETIME, $latestPostTime) . '</td>';
                echo '</tr>';
            }
        }
        else {
            echo "<tr><td colspan=\"4\" align=\"left\"> " . _NO_TIMEFRAME_POSTS . " </td></tr>";
        }

        echo "</tbody></table></div></div></div></div></div>";
        ?>

        <!-- Begin: Forum Jump -->
<div class="<?php echo $boardclass; ?>_bt_cvr1">
<div class="<?php echo $boardclass; ?>_bt_cvr2">
<div class="<?php echo $boardclass; ?>_bt_cvr3">
<div class="<?php echo $boardclass; ?>_bt_cvr4">
<div class="<?php echo $boardclass; ?>_bt_cvr5">
        <table class = "fb_blocktable" id="fb_bottomarea" border = "0" cellspacing = "0" cellpadding = "0">
            <thead>
                <tr>
                    <th  class = "th-right">
                        <?php

                        //(JJ) FINISH: CAT LIST BOTTOM
                        if ($fbConfig->enableforumjump)
                            require_once (KUNENA_ABSSOURCESPATH . 'kunena.forumjump.php');
                        ?>
                    </th>
                </tr>
            </thead>
			<tbody><tr><td></td></tr></tbody>
        </table>
</div>
</div>
</div>
</div>
</div>
<!-- Finish: Forum Jump -->
