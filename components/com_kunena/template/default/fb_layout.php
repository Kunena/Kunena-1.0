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

// ################################################################
/**
 *  Function to print the pathway
 *  @param object    database object
 *  @param object    category object
 *  @param int        the post id
 *  @param boolean    set title
 */
function KUNENA_print_pathway(&$database, $obj_fb_cat, $bool_set_title, $obj_post = 0) {
    echo '<div class="fb_pathway">' . fb_get_pathway($database, $obj_fb_cat, $bool_set_title, $obj_post) . '</div>';
}
/**
 *  Function to print the pathway
 *  @param object    database object
 *  @param object    category object
 *  @param int        the post id
 *  @param boolean    set title
 */
function KUNENA_get_pathway(&$database, $obj_fb_cat, $bool_set_title, $obj_post = 0)
{
    global $mainframe, $fbIcons;
	$fbConfig =& CKunenaConfig::getInstance();
    //Get the Category's parent category name for breadcrumb
    $database->setQuery('SELECT name,id FROM #__fb_categories WHERE id=' . $obj_fb_cat->getParent());
    $database->loadObject($objCatParentInfo);
    	check_dberror("Unable to load categories.");
    //get the Moderator list for display
    $database->setQuery('SELECT * FROM #__fb_moderation LEFT JOIN #__users ON #__users.id=#__fb_moderation.userid WHERE #__fb_moderation.catid=' . $obj_fb_cat->getId());
    $modslist = $database->loadObjectList();
    	check_dberror("Unable to load moderators.");
    //    echo '<div class="fb_pathway">';
    // List of Forums
    // show folder icon
    $return = '<img src="' . KUNENA_URLIMAGESPATH . 'folder.gif" border="0" alt="' . _GEN_FORUMLIST . '" style="vertical-align: middle;" />&nbsp;';
    // link to List of Forum Categories
    $return .= '&nbsp;' . fb_Link::GetKunenaLink(_GEN_FORUMLIST) . '<br />';

    // List of    Categories
    if ($objCatParentInfo)
    {
        if ($bool_set_title)
            $mainframe->setPageTitle(stripslashes($objCatParentInfo->name) . ' - ' . stripslashes($obj_fb_cat->getName()) . ' - ' . stripslashes($fbConfig->board_title));

        // show lines
        $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'tree-end.gif" alt="|-" border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'folder.gif" alt="' . $objCatParentInfo->name . '" border="0" style="vertical-align: middle;" />&nbsp;';
        // link to Category
        $return .= '&nbsp;'. fblink::GetCategoryLink('listcat', $objCatParentInfo->id, $objCatParentInfo->name) . '<br />';
        $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'tree-blank.gif" alt="| " border="0" style="vertical-align: middle;" />';
    }
    else
    {
        if ($bool_set_title)
            $mainframe->setPageTitle(stripslashes($obj_fb_cat->getName()) . ' - ' . stripslashes($fbConfig->board_title));
    }

    // Forum
    // show lines
    $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'tree-end.gif" alt="|-" border="0" style="vertical-align: middle;" />';
    $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'folder.gif" alt="+" border="0" style="vertical-align: middle;" />&nbsp;';
    // Link to forum
    $return .= '&nbsp;' . fbLink::GetCategoryLink('listcat', $obj_fb_cat->getId(), $obj_fb_cat->getName());

    //check if this forum is locked
    if ($obj_fb_cat->getLocked()) {
        $return .= isset($fbIcons['forumlocked']) ? '&nbsp;&nbsp;<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['forumlocked'] . '" border="0" alt="'
            . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '"/>' : '    <img src="' . KUNENA_URLIMAGESPATH . 'lock.gif"    border="0" width="13" height="13" alt="' . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '">';
    }

    // check if this forum is reviewed
    if ($obj_fb_cat->getReview()) {
        $return .= isset($fbIcons['forumreviewed']) ? '&nbsp;&nbsp;<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['forumreviewed']
            . '" border="0" alt="' . _GEN_REVIEWED . '" title="' . _GEN_REVIEWED . '"/>' : '    <img src="' . KUNENA_URLIMAGESPATH . 'review.gif" border="0" width="15" height="15" alt="' . _GEN_REVIEWED . '" title="' . _GEN_REVIEWED . '">';
    }

    //check if this forum is moderated
    if ($obj_fb_cat->getModerated())
    {
        $return .= isset($fbIcons['forummoderated']) ? '&nbsp;&nbsp;<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['forummoderated']
            . '" border="0" alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '"/>' : '    <img src="' . KUNENA_URLEMOTIONSPATH . 'moderate.gif" border="0"  alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '"/>';
        $text = '';

        if (count($modslist) > 0)
        {
            foreach ($modslist as $mod) {
                $text = $text . ', ' . $mod->username;
            }

            $return .= '&nbsp;(' . _GEN_MODERATORS . ': ' . ltrim($text, ",") . ')';
        }
    }

    if ($obj_post != 0)
    {
        if ($bool_set_title)
            $mainframe->setPageTitle(stripslashes($obj_post->subject) . ' - ' . stripslashes($fbConfig->board_title));

        // Topic
        // show lines
        $return .= '<br />&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'tree-blank.gif" alt="| " border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'tree-blank.gif" alt="| " border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'tree-end.gif" alt="|-" border="0" style="vertical-align: middle;" />';
        $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'folder.gif" alt="+" border="0" style="vertical-align: middle;" />&nbsp;';
        $return .= '&nbsp;<b>' . $obj_post->subject . '</b>';

        // Check if the Topic is locked?
        if ((int)$obj_post->locked != 0) {
            $return .= '&nbsp;<img src="' . KUNENA_URLIMAGESPATH . 'lock.gif"    border="0" width="13" height="13" alt="' . _GEN_LOCKED_TOPIC . '" title="' . _GEN_LOCKED_TOPIC . '"/>';
        }
    }

    //    echo '</div>';
    return $return;
}
/**
 * Function to generate the page list of a forum
 */

/**
 * Function  that get the menu used in the header of our board
 * @param int $cbitemid
 *             Community builder itemid, used for linking to cb profile
 * @param array $fbConfig
 * @param array $fbIcons
 * @param int $my_id
 *             The user id
 * @param int $type
 *             What kind of header do you want to print: 1: default (home/profile/latest posts/faq), 2: extended1 (home/profile/view/pending messages/faq) ,3:extended2 (home/profile/reply/view/pdf/faq)
 * @param string $view
 *             The view the user is currently using, only needs to be pass when type==3 or type==2
 * @param int $catid
 *             Only needs to be passed when type==3 or type==2
 * @param int $id
 *             Only needs to be passed when type==3 or type==2
 * @param int $thread
 *             Only needs to be passed when type==3 or type==2 (well actually just give 0 when type==2)
 * @param boolean $is_moderator
 *             Only needs to be passed when type==2
 * @param int $numPending
 *             Number of pending messages, only needs to be passed when type==2
 * @return String $header
 *             The menu :-)
 */
function KUNENA_get_menu($cbitemid, $fbConfig, $fbIcons, $my_id, $type, $view = "", $catid = 0, $id = 0, $thread = 0, $is_moderator = false, $numPending = 0)
{
    $header = '<div id="fb_topmenu" >';
    $header .= CKunenaLink::GetCategoryListLink('<span>'.(isset($fbIcons['home']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['home'] . '" border="0" alt="' . _KUNENA_CATEGORIES . '"  title="' . _KUNENA_CATEGORIES . '" />' : _KUNENA_CATEGORIES).'</span>');

    if ($my_id != 0)
    {
        $header .= CKunenaLink::GetMyProfileLink( $fbConfig, $my_id, isset($fbIcons['profile']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['profile'] . '" border="0" alt="' . _GEN_MYPROFILE . '" title="' . _GEN_MYPROFILE . '"/>' : _GEN_MYPROFILE);
    }

    switch ($type)
    {
        case 3:
            /* DISABLE MENU
            $header.= '<a href="'.sefRelToAbs(KUNENA_LIVEURLREL.'&amp;func=post&amp;do=reply&amp;replyto='.$thread.'&amp;catid='.$catid).'" >';
            $header.= isset($fbIcons['menureply']) ? '<img src="' . KUNENA_URLICONSPATH . ''.$fbIcons['menureply'].'" border="0" alt="'._GEN_POST_REPLY.'" title="'._GEN_POST_REPLY.'"/>' : _GEN_POST_REPLY;
            $header.= '</a>';
           */
//Disable threaded view option alltogether for Kunena
//            if ($view == "flat") {
//    			$header .= CKunenaLink::GetViewLink('view', $id, $catid, 'threaded', (isset($fbIcons['threadedview']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['threadedview'] . '" border="0" alt="' . _GEN_THREADED_VIEW . '" title="' . _GEN_THREADED_VIEW . '"/>' : _GEN_THREADED_VIEW));
//            }
//            else
//            {
//                $header .= CKunenaLink::GetViewLink('view', $id, $catid, 'flat', (isset($fbIcons['flatview']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['flatview'] . '" border="0" alt="' . _GEN_FLAT_VIEW . '" title="' . _GEN_FLAT_VIEW . '"/>' : _GEN_FLAT_VIEW));
//            }
            break;

        case 2:
//Disable threaded view option alltogether for Kunena
//            if ($view == "flat")
//            {
//    			$header .= CKunenaLink::GetViewLink('showcat', $id, $catid, 'threaded', (isset($fbIcons['threadedview']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['threadedview'] . '" border="0" alt="' . _GEN_THREADED_VIEW . '" title="' . _GEN_THREADED_VIEW . '"/>' : _GEN_THREADED_VIEW));
//            }
//			else
//			{
//                $header .= CKunenaLink::GetViewLink('showcat', $id, $catid, 'flat', (isset($fbIcons['flatview']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['flatview'] . '" border="0" alt="' . _GEN_FLAT_VIEW . '" title="' . _GEN_FLAT_VIEW . '"/>' : _GEN_FLAT_VIEW));
//			}
            if ($is_moderator)
            {
                if ($numPending > 0)
                {
                    $numcolor = '<font color="red">';
                    $header .= CKunenaLink::GetPendingMessagesLink( $catid, (isset($fbIcons['pendingmessages']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['pendingmessages'] . '" border="0" alt="' . $numPending . ' ' . _SHOWCAT_PENDING . '" />' : $numcolor . '' . $numPending . '</font> ' . _SHOWCAT_PENDING));
                }
            }

            break;

        case 1:
        default:
            $header .= CKunenaLink::GetShowLatestLink( (isset($fbIcons['showlatest']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['showlatest'] . '" border="0" alt="' . _GEN_LATEST_POSTS . '" title="' . _GEN_LATEST_POSTS . '"/>' : _GEN_LATEST_POSTS));
            break;
    }

    if ($fbConfig->enablerulespage)
    {
        $header .= CKunenaLink::GetRulesLink($fbConfig, (isset($fbIcons['rules']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['rules'] . '" border="0" alt="' . _GEN_RULES . '" title="' . _GEN_RULES . '"/>' : _GEN_RULES));
    }
	if ($fbConfig->enablehelppage)
    {
        $header .= CKunenaLink::GetHelpLink($fbConfig, (isset($fbIcons['help']) ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['help'] . '" border="0" alt="' . _GEN_HELP . '" title="' . _GEN_HELP . '"/>' : _GEN_HELP));
	}
    $header .= '</div>';
    return $header;
}

function getSearchBox()
{
    $return = '<div id="fb_searchbox"><form action="' . sefRelToAbs(KUNENA_LIVEURLREL . '&amp;func=search') . '" name="searchFB" method="post">';
    $boxsize = strlen(_GEN_SEARCH_BOX);

    if ($boxsize <= 15)
        $boxsize = 15;

   $return .= '<input class="fb_search_inputbox fbs" type="text" name="q" size="'. $boxsize . '" value="' . _GEN_SEARCH_BOX . '" onblur="if(this.value==\'\') this.value=\'' . _GEN_SEARCH_BOX . '\';" onfocus="if(this.value==\'' . _GEN_SEARCH_BOX . '\') this.value=\'\';" />';
	$return .= ' <input type="submit" value="'._KUNENA_GO.'" name="submit" class="fb_search_button fbs"/>';
    $return .= '</form></div>';
    return $return;
}
?>
