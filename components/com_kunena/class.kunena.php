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
*/

// Dont allow direct linking
defined ('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/**
*@desc Getting the correct Itemids, for components required
*/

// Detect charset
if (defined('JPATH_ADMINISTRATOR')) define('KUNENA_CHARSET', 'UTF-8');
else define('KUNENA_CHARSET',  ( defined( '_ISO' ) ? strtoupper( str_replace( "charset=", "", _ISO ) ) : 'ISO-8859-1' ) );

// Shortcuts to all the path we have:
define('KUNENA_JABSPATH', $mainframe->getCfg('absolute_path'));

// Joomla absolute path
define('KUNENA_JLIVEURL', $mainframe->getCfg('live_site'));

// Joomla template dir
define('KUNENA_JTEMPLATEPATH', KUNENA_JABSPATH. "/templates/" . $mainframe->getTemplate());
define('KUNENA_JTEMPLATEURL', KUNENA_JLIVEURL. "/templates/".$mainframe->getTemplate());

//Kunena
$Itemid = intval(mosGetParam($_REQUEST, 'Itemid'));

//check if we have all the itemid sets. if so, then no need for DB call

// get kunena configuration class
require_once ($mainframe->getCfg("absolute_path") . "/components/com_kunena/lib/kunena.config.class.php");

$fbConfig =& CKunenaConfig::getInstance();

if (!defined("KUNENA_COMPONENT_ITEMID")) {
        $database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_kunena' AND published = 1");
        $Itemid = $database->loadResult();

        if ($Itemid < 1) {
            $Itemid = 0;
            }

    define("KUNENA_COMPONENT_ITEMID", (int)$Itemid);
    define("KUNENA_COMPONENT_ITEMID_SUFFIX", "&amp;Itemid=" . KUNENA_COMPONENT_ITEMID);

    //JomSocial
    if ($fbConfig->pm_component == 'jomsocial' || $fbConfig->fb_profile == 'jomsocial' || $fbConfig->avatar_src == 'jomsocial')
    {
    	// Only proceed if jomSocial is really installed
	    if ( file_exists( $mainframe->getCfg( 'absolute_path' ) . '/components/com_community/libraries/core.php' ) )
	    {
	        $database->setQuery("SELECT id FROM #__menu WHERE link LIKE 'index.php?option=com_community%' AND published='1' LIMIT 1");
	        $JOMSOCIAL_Itemid = $database->loadResult();
	        	check_dberror('Unable to load jomSocial item id');

	        define("KUNENA_JOMSOCIAL_ITEMID", (int)$JOMSOCIAL_Itemid);
	        define("KUNENA_JOMSOCIAL_ITEMID_SUFFIX", "&amp;Itemid=" . KUNENA_JOMSOCIAL_ITEMID);

	        // Prevent JomSocial from loading their jquery library - we got one loaded already
	        define( 'C_ASSET_JQUERY', 1 );

			include_once(JPATH_ROOT.'/components/com_community/libraries/core.php');
			include_once(JPATH_ROOT.'/components/com_community/libraries/messaging.php');

			//PM popup requires JomSocial css to be loaded from selected template
			$config =& CFactory::getConfig();
			$mainframe->addCustomHeadTag('<link type="text/css" rel="stylesheet" href="'.KUNENA_JLIVEURL.'/components/com_community/assets/window.css" />');
			$mainframe->addCustomHeadTag('<link type="text/css" rel="stylesheet" href="'.KUNENA_JLIVEURL.'/components/com_community/templates/'.$config->get('template').'/css/style.css" />');
	    }
	    else
	    {
	    	// JomSocial not present reset config settings to avoid problems
	    	$fbConfig->pm_component = $fbConfig->pm_component == 'jomsocial' ? 'none' : $fbConfig->pm_component;
	    	$fbConfig->fb_profile = $fbConfig->fb_profile == 'jomsocial' ? 'kunena' : $fbConfig->fb_profile;
	    	$fbConfig->avatar_src = $fbConfig->avatar_src == 'jomsocial' ? 'kunena' : $fbConfig->avatar_src;

	    	// Do not save new config - thats a task for the backend
	    	// This is just a catch all in case it is not present
	    }
    }

    //Community Builder 1.2 - older 1.1 integration no longer supported
	if ($fbConfig->pm_component == 'cb' || $fbConfig->fb_profile == 'cb' || $fbConfig->avatar_src == 'cb')
    {
		// Get Community Builder compability
		require_once ($mainframe->getCfg("absolute_path") . "/components/com_kunena/lib/kunena.communitybuilder.php");
		global $kunenaProfile;
		$kunenaProfile =& CkunenaCBProfile::getInstance();
    }

    //Clexus PM
    if ($fbConfig->pm_component == 'clexuspm' || $fbConfig->fb_profile == 'clexuspm') {
        $database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_mypms' AND published=1");
        $CPM_Itemid = $database->loadResult();
        	check_dberror('Unable to load Clexus item id');

        define("KUNENA_CPM_ITEMID", (int)$CPM_Itemid);
        define("KUNENA_CPM_ITEMID_SUFFIX", "&amp;Itemid=" . KUNENA_CPM_ITEMID);
        }

    // UddeIM
    if ($fbConfig->pm_component == 'uddeim') {
        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_uddeim'");
        $UIM_itemid = $database->loadResult();
                	check_dberror('Unable to load uddeim item id');

        define("KUNENA_UIM_ITEMID", (int)$UIM_itemid);
        define("KUNENA_UIM_ITEMID_SUFFIX", "&amp;Itemid=" . KUNENA_UIM_ITEMID);
        }

    // MISSUS
    if ($fbConfig->pm_component == 'missus') {
        $database->setQuery("SELECT id FROM #__menu WHERE link='index.php?option=com_missus'");
        $MISSUS_itemid = $database->loadResult();
                	check_dberror('Unable to load missus item id');

        define("KUNENA_MISSUS_ITEMID", (int)$MISSUS_itemid);
        define("KUNENA_MISSUS_ITEMID_SUFFIX", "&amp;Itemid=" . KUNENA_MISSUS_ITEMID);
        }

    // PROFILE LINK
    if ($fbConfig->fb_profile == "jomsocial") {
        $profilelink = 'index.php?option=com_community&amp;view=profile&amp;userid=';
        define("KUNENA_PROFILE_LINK_SUFFIX", "index.php?option=com_community&amp;view=profile&amp;Itemid=" . KUNENA_JOMSOCIAL_ITEMID . "&amp;userid=");
        }
    else if ($fbConfig->fb_profile == "cb") {
        $profilelink = 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=';
        define("KUNENA_PROFILE_LINK_SUFFIX", "index.php?option=com_comprofiler&amp;task=userProfile" . KUNENA_CB_ITEMID_SUFFIX . "&amp;user=");
        }
    else if ($fbConfig->fb_profile == "clexuspm") {
        $profilelink = 'index.php?option=com_mypms&amp;task=showprofile&amp;user=';
        define("KUNENA_PROFILE_LINK_SUFFIX", "index.php?option=com_mypms&amp;task=showprofile&amp;Itemid=" . KUNENA_CPM_ITEMID . "&amp;user=");
        }
    else {
        $profilelink = 'index.php?option=com_kunena&amp;func=fbprofile&amp;task=showprf&amp;userid=';
        define("KUNENA_PROFILE_LINK_SUFFIX", "index.php?option=com_kunena&amp;func=fbprofile&amp;task=showprf&amp;Itemid=" . KUNENA_COMPONENT_ITEMID . "&amp;userid=");
        }
    }

/*       _\|/_
         (o o)
 +----oOO-{_}-OOo--------------------------------+
 | Now we have the components Itemids everywhere |
 | Please use these constants where ever needed  |
 +----------------------------------------------*/

// Kunena live url
define('KUNENA_LIVEURL', KUNENA_JLIVEURL . '/index.php?option=com_kunena' . KUNENA_COMPONENT_ITEMID_SUFFIX);
define('KUNENA_CLEANLIVEURL', KUNENA_JLIVEURL . '/index2.php?option=com_kunena&amp;no_html=1' . KUNENA_COMPONENT_ITEMID_SUFFIX);
define('KUNENA_LIVEURLREL', 'index.php?option=com_kunena' . KUNENA_COMPONENT_ITEMID_SUFFIX);
define('KUNENA_ABSPATH', KUNENA_JABSPATH . '/components/com_kunena');

// Kunena absolute path
define('KUNENA_ABSSOURCESPATH', KUNENA_ABSPATH . '/lib/');

// Kunena souces absolute path
define('KUNENA_DIRECTURL', KUNENA_JLIVEURL . '/components/com_kunena');

// Kunena direct url
define('KUNENA_URLSOURCESPATH', KUNENA_DIRECTURL . '/lib/');

// Kunena sources url
define('KUNENA_LANG', $mainframe->getCfg('lang'));
define('KUNENA_ABSADMPATH', KUNENA_JABSPATH . '/administrator/components/com_kunena');

if (!defined("KUNENA_JCSSURL")) {
    $database->setQuery("SELECT template FROM #__templates_menu where client_id ='0'");
    $current_stylesheet = $database->loadResult();
    define('KUNENA_JCSSURL', KUNENA_JLIVEURL . '/templates/' . $current_stylesheet . '/css/template_css.css');
}

// Kunena uploaded files directory
define('KUNENA_ABSUPLOADEDPATH', KUNENA_JABSPATH . '/images/fbfiles');
define('KUNENA_LIVEUPLOADEDPATH', KUNENA_JLIVEURL . '/images/fbfiles');


// now continue with other paths

$fb_user_template = strval(mosGetParam($_COOKIE, 'fb_user_template', ''));
$fb_user_img_template = strval(mosGetParam($_COOKIE, 'fb_user_img_template', ''));
// don't allow directory travelling
$fb_user_template = strtr($fb_user_template, '\\/', '');
$fb_user_img_template = strtr($fb_user_template, '\\/', '');

if (strlen($fb_user_template) > 0 && file_exists(KUNENA_ABSPATH . '/template/' . $fb_user_template))
{
    $fb_cur_template = $fb_user_template;
    }
else if (file_exists(KUNENA_ABSPATH . '/template/' . $fbConfig->template))
{
    $fb_cur_template = $fbConfig->template;
    }
else
{
    $fb_cur_template = 'default_ex';
    }

if (strlen($fb_user_img_template) > 0 && file_exists(KUNENA_ABSPATH . '/template/' . $fb_user_template . '/images'))
{
    $fb_cur_img_template = $fb_user_img_template;
    }
else if (file_exists(KUNENA_ABSPATH . '/template/' . $fbConfig->templateimagepath . '/images'))
{
    $fb_cur_img_template = $fbConfig->templateimagepath;
    }
else
{
    $fb_cur_img_template = 'default_ex';
    }

// only for preview module - maybe used later by users to change template

define('KUNENA_ABSTMPLTPATH', KUNENA_ABSPATH . '/template/' . $fb_cur_template);
define('KUNENA_ABSTMPLTMAINIMGPATH', KUNENA_ABSPATH . '/template/' . $fb_cur_img_template);

// IMAGES ABSOLUTE PATH
if (is_dir(KUNENA_ABSTMPLTMAINIMGPATH . '/images/' . KUNENA_LANG . '')) {
    define('KUNENA_ABSIMAGESPATH', KUNENA_ABSTMPLTMAINIMGPATH . '/images/' . KUNENA_LANG . '/');
    }
else {
    define('KUNENA_ABSIMAGESPATH', KUNENA_ABSTMPLTMAINIMGPATH . '/images/english/');
    }

// absolute images path
define('KUNENA_ABSICONSPATH', KUNENA_ABSIMAGESPATH . 'icons/');

// absolute icons path
define('KUNENA_ABSEMOTIONSPATH', KUNENA_ABSIMAGESPATH . 'emoticons/');

// absolute emoticons path
define('KUNENA_ABSGRAPHPATH', KUNENA_ABSIMAGESPATH . 'graph/');

// absolute graph path
define('KUNENA_ABSRANKSPATH', KUNENA_ABSIMAGESPATH . 'ranks/');

// absolute ranks path
define('KUNENA_ABSCATIMAGESPATH', KUNENA_ABSUPLOADEDPATH . '/' . $fbConfig->catimagepath); // Kunena category images absolute path

define('KUNENA_TMPLTURL', KUNENA_DIRECTURL . '/template/' . $fb_cur_template);
define('KUNENA_TMPLTMAINIMGURL', KUNENA_DIRECTURL . '/template/' . $fb_cur_img_template);

// IMAGES URL PATH
define('KUNENA_TMPLTCSSURL', KUNENA_TMPLTURL . '/kunena.forum.css');

if (is_dir(KUNENA_ABSTMPLTMAINIMGPATH . '/images/' . KUNENA_LANG . '')) {
    define('KUNENA_URLIMAGESPATH', KUNENA_TMPLTMAINIMGURL . '/images/' . KUNENA_LANG . '/');
    }
else {
    define('KUNENA_URLIMAGESPATH', KUNENA_TMPLTMAINIMGURL . '/images/english/');
    }

// url images path
define('KUNENA_URLICONSPATH', KUNENA_URLIMAGESPATH . 'icons/');

// url icons path
define('KUNENA_URLEMOTIONSPATH', KUNENA_URLIMAGESPATH . 'emoticons/');

// url emoticons path
define('KUNENA_URLGRAPHPATH', KUNENA_URLIMAGESPATH . 'graph/');

// url graph path
define('KUNENA_URLRANKSPATH', KUNENA_URLIMAGESPATH . 'ranks/');

// url ranks path
define('KUNENA_URLCATIMAGES', KUNENA_LIVEUPLOADEDPATH . '/' . $fbConfig->catimagepath); // Kunena category images direct url

if (file_exists(KUNENA_ABSTMPLTPATH . '/js/jquery-1.3.2.min.js'))
{
    define('KUNENA_JQURL', KUNENA_DIRECTURL . '/template/' . $fb_cur_template . '/js/jquery-1.3.2.min.js');
}
else
{
    define('KUNENA_JQURL', KUNENA_DIRECTURL . '/template/default/js/jquery-1.3.2.min.js');
}

if (file_exists(KUNENA_ABSTMPLTPATH . '/js/kunenaforum.js'))
{
	define('KUNENA_COREJSPATH', '/components/com_kunena/template/' . $fb_cur_template . '/js/kunenaforum.js');
	define('KUNENA_COREJSURL', KUNENA_DIRECTURL . '/template/' . $fb_cur_template . '/js/kunenaforum.js');
}
else
{
	define('KUNENA_COREJSPATH', '/components/com_kunena/template/default/js/kunenaforum.js');
	define('KUNENA_COREJSURL', KUNENA_DIRECTURL . '/template/default/js/kunenaforum.js');
}

function KUNENA_fmodReplace($x, $y) { //function provided for older PHP versions which do not have an fmod function yet
    $i = floor($x / $y);
    // r = x - i * y
    return $x - $i * $y;
    }

function KUNENA_check_image_type(&$type) {
    switch ($type)
    {
        case 'jpeg':
        case 'pjpeg':
        case 'jpg':
        case 'JPEG':
        case 'PJPEG':
        case 'JPG':
            return '.jpg';

            break;

        case 'gif':
        case 'GIF':
            return '.gif';

            break;

        case 'png':
        case 'PNG':
            return '.png';

            break;
    }

    return false;
    }

function getFBGroupName($id) {
    global $database;
    $gr = '';
    $database->setQuery("select id, title from #__fb_groups as g, #__fb_users as u where u.group_id = g.id and u.userid=".(int)$id);
    $database->loadObject($gr);

    if ($gr == NULL) {
	$gr = new stdClass();
	$gr->id = 0;
	$gr->title = _VIEW_VISITOR;
    }
    return $gr;
}

class CKunenaTools {
    var $id = null;

/*
    function fbGetCurrentTime () {
    	// tells current FB internal representing time
        $fbConfig =& CKunenaConfig::getInstance();
        return time() + ($fbConfig->board_ofset * 3600);
    }
*/
    function fbGetInternalTime ($time=null) {
    	// tells internal FB representing time from UTC $time
        $fbConfig =& CKunenaConfig::getInstance();
        // Prevent zeroes
        if($time===0) {
          return 0;
        }
        if($time===null) {
          $time = time();
        }
        return $time + ($fbConfig->board_ofset * 3600);
    }

    function fbGetShowTime ($time=null, $space='FB') {
    	// converts internal (FB)|UTC representing time to display time
    	// could consider user properties (zones) for future
        $fbConfig =& CKunenaConfig::getInstance();
        // Prevent zeroes
        if($time===0) {
          return 0;
        }
        if($time===null) {
          $time = CKunenaTools::fbGetInternalTime();
          $space = 'FB';
        }
        if($space=='UTC') {
          return $time + ($fbConfig->board_ofset * 3600);
        }
        return $time;
    }

    function whoisID($id) {
        global $database, $mosConfig_live_site;

        $id = intval($database->getEscaped($id));
        $database->setQuery("select username from #__users where id=$id");
        return $database->loadResult();
        }

    function reCountBoards() {
        global $database;
        include_once (KUNENA_ABSSOURCESPATH . 'kunena.db.iterator.class.php');

        //reset all stats to 0
        $database->setQuery("UPDATE `#__fb_categories` SET `id_last_msg`='0',`time_last_msg`='0',`numTopics`='0',`numPosts`='0'");
        $database->query();
        	check_dberror("Unable to update categories.");

        $database->setQuery("select id, time, parent, catid from #__fb_messages where hold=0 AND moved=0 order by id asc");
        $messages_iter = new fb_DB_Iterator($database);
        	check_dberror("Unable to load messages.");

        $database->setQuery("select id, parent, numTopics, numPosts,id_last_msg, time_last_msg from #__fb_categories order by id asc");
        $cats = $database->loadObjectList();
        	check_dberror("Unable to load messages.");

        foreach ($cats as $c) {
            $ctg[$c->id] = $c;
            }

        $i=0;
        while ($messages_iter->loadNextObject($l)) {
        	$i++;
            //if($i==100) { die(); }
            $cat_l = $l->catid;

            while ($cat_l) {
                if ($l->parent == 0) {
                    $ctg[$cat_l]->numTopics++;
                    }
                else {
                    $ctg[$cat_l]->numPosts++;
                    }

                $ctg[$cat_l]->id_last_msg = $l->id;
                $ctg[$cat_l]->time_last_msg = $l->time;
                $cat_l = $ctg[$cat_l]->parent;
                }
            }

        // now back to db
        foreach ($ctg as $cc) {
            $database->setQuery(
                "UPDATE `#__fb_categories` SET `time_last_msg`='" . $cc->time_last_msg . "',`id_last_msg`='" . $cc->id_last_msg . "',`numTopics`='" . $cc->numTopics . "',`numPosts`='" . $cc->numPosts . "' WHERE (`id`='" . $cc->id . "') ");
            $database->query();
            echo $database->getErrorMsg();
            }
        $messages_iter->Free();

        }

    function updateNameInfo()
    {
        global $database;
        $fbConfig =& CKunenaConfig::getInstance();

        $fb_queryName = $fbConfig->username ? "username" : "name";

	    $query = "UPDATE #__fb_messages AS m, #__users AS u
	    			SET m.name = u.$fb_queryName
					WHERE m.userid = u.id";
        $database->setQuery($query);
        $database->query();
        	check_dberror ("Unable to update user name information");
    }

    function modifyCategoryStats($msg_id, $msg_parent, $msg_time, $msg_cat) {
        global $database;
        $database->setQuery("select id, parent, numTopics, numPosts,id_last_msg, time_last_msg from #__fb_categories order by id asc");
        $cats = $database->loadObjectList();
        	check_dberror("Unable to load categories.");

        foreach ($cats as $c) {
            $ctg[$c->id] = $c;
            }

        while ($msg_cat) {
            // traverse parental from orig msg_cat
            if ($msg_parent == 0) {
                $ctg[$msg_cat]->numTopics++;
                }
            else {
                $ctg[$msg_cat]->numPosts++;
                }

            $ctg[$msg_cat]->id_last_msg = $msg_id;
            $ctg[$msg_cat]->time_last_msg = $msg_time;

            // store to db (only changed)
            $database->setQuery(
                "UPDATE `#__fb_categories`"
                ." SET `time_last_msg`='" . $ctg[$msg_cat]->time_last_msg . "'"
                .",`id_last_msg`='" . $ctg[$msg_cat]->id_last_msg . "'"
                .",`numTopics`='" . $ctg[$msg_cat]->numTopics . "'"
                .",`numPosts`='" . $ctg[$msg_cat]->numPosts . "'"
                ." WHERE (`id`='" . $ctg[$msg_cat]->id . "') ");
            $database->query();
            echo $database->getErrorMsg();

            // parent
            $msg_cat = $ctg[$msg_cat]->parent;
            }

        return;
        }

    function decreaseCategoryStats($msg_id, $msg_cat) {
        //topic : 1 , message = 0
        global $database;
        $database->setQuery("select id, parent, numTopics, numPosts,id_last_msg, time_last_msg from #__fb_categories order by id asc");
        $cats = $database->loadObjectList();
        	check_dberror("Unable to load categories.");

        foreach ($cats as $c) {
            $ctg[$c->id] = $c;
            }

        $database->setQuery('select id FROM #__fb_messages WHERE id=' . $msg_id . ' OR thread=' . $msg_id);

        $msg_ids = $database->loadResultArray();
        	check_dberror("Unable to load messages.");

        $cntTopics = 0;
        $cntPosts = 0;

        if (count($msg_ids) > 0) {
            foreach ($msg_ids as $msg) {
                if ($msg == $msg_id) {
                    $cntTopics = 1;
                    }
                else {
                    $cntPosts++;
                    }
                }
            }

        while ($msg_cat) {

            unset($lastMsgInCat);
            $database->setQuery("select id, time from #__fb_messages where catid={$msg_cat} and (thread <> {$msg_id} AND id<>{$msg_id}) order by time desc limit 1;");
            $database->loadObject($lastMsgInCat);
            	check_dberror("Unable to load messages.");

            $ctg[$msg_cat]->numTopics = (int) ($ctg[$msg_cat]->numTopics - $cntTopics);
            $ctg[$msg_cat]->numPosts = (int) ($ctg[$msg_cat]->numPosts - $cntPosts);

            $ctg[$msg_cat]->id_last_msg = $lastMsgInCat->id;
            $ctg[$msg_cat]->time_last_msg = $lastMsgInCat->time;

            $msg_cat = $ctg[$msg_cat]->parent;
            }

        // now back to db
        foreach ($ctg as $cc) {
            $database->setQuery("UPDATE `#__fb_categories` SET `time_last_msg`='" . $cc->time_last_msg . "',`id_last_msg`='" . $cc->id_last_msg . "',`numTopics`='" . $cc->numTopics . "',`numPosts`='" . $cc->numPosts . "' WHERE `id`='" . $cc->id . "' ");
            $database->query();
            	check_dberror("Unable to update categories.");
            }

        return;
        }

    function showBulkActionCats($disabled = 1) {
        global $database;

        $options = array ();
        $options[] = mosHTML::makeOption('0', "&nbsp;");
        $lists['parent'] = KUNENA_GetAvailableForums(0, "", $options, $disabled);

        echo $lists['parent'];
        }

    function fbDeletePosts($isMod, $return) {
        global $my, $database;

        if (!CKunenaTools::isModOrAdmin() && !$isMod) {
            mosRedirect($return, _POST_NOT_MODERATOR);
            }

        $items = fbGetArrayInts("fbDelete");
        $dellattach = 1;

        // start iterating here
        foreach ($items as $id => $value) {
            $database->setQuery('SELECT id,catid,parent,thread,subject,userid FROM #__fb_messages WHERE id=' . $id);

            if (!$database->query()) {
                return -2;
                }

            $database->loadObject($mes);
            $thread = $mes->thread;

            if ($mes->parent == 0) {
                // this is the forum topic; if removed, all children must be removed as well.
                $children = array ();
                $userids = array ();
                $database->setQuery('SELECT userid,id, catid FROM #__fb_messages WHERE thread=' . $id . ' OR id=' . $id);

                foreach ($database->loadObjectList() as $line) {
                    $children[] = $line->id;

                    if ($line->userid > 0) {
                        $userids[] = $line->userid;
                        }
                    }

                $children = implode(',', $children);
                }
            else {
                //this is not the forum topic, so delete it and promote the direct children one level up in the hierarchy
                $database->setQuery('UPDATE #__fb_messages SET parent=\'' . $mes->parent . '\' WHERE parent=\'' . $id . '\'');

                if (!$database->query()) {
                    return -1;
                    }

                $children = $id;
                $userids = $mes->userid > 0 ? $mes->userid : '';
                }

            //Delete the post (and it's children when it's the first post)
            $database->setQuery('DELETE FROM #__fb_messages WHERE id=' . $id . ' OR thread=' . $id);

            if (!$database->query()) {
                return -2;
                }

            // now update stats
            CKunenaTools::decreaseCategoryStats($id, $mes->catid);

            //Delete message text(s)
            $database->setQuery('DELETE FROM #__fb_messages_text WHERE mesid IN (' . $children . ')');

            if (!$database->query()) {
                return -3;
                }

            //Update user post stats
            if (count($userids) > 0) {
                $userids = implode(',', $userids);
                $database->setQuery('UPDATE #__fb_users SET posts=posts-1 WHERE userid IN (' . $userids . ')');

                if (!$database->query()) {
                    return -4;
                    }
                }

            //Delete (possible) ghost post
            $database->setQuery('SELECT mesid FROM #__fb_messages_text WHERE message=\'catid=' . $mes->catid . '&amp;id=' . $id . '\'');
            $int_ghost_id = $database->loadResult();

            if ($int_ghost_id > 0) {
                $database->setQuery('DELETE FROM #__fb_messages WHERE id=' . $int_ghost_id);
                $database->query();
                $database->setQuery('DELETE FROM #__fb_messages_text WHERE mesid=' . $int_ghost_id);
                $database->query();
                }

            //Delete attachments
            if ($dellattach) {
                $database->setQuery('SELECT filelocation FROM #__fb_attachments WHERE mesid IN (' . $children . ')');
                $fileList = $database->loadObjectList();
                	check_dberror("Unable to load attachments.");

                if (count($fileList) > 0) {
                    foreach ($fileList as $fl) {
                        unlink ($fl->filelocation);
                        }

                    $database->setQuery('DELETE FROM #__fb_attachments WHERE mesid IN (' . $children . ')');
                    $database->query();
                    }
                }
            } //end foreach
            CKunenaTools::reCountBoards();

            mosRedirect($return, _KUNENA_BULKMSG_DELETED);
        }

    function isModOrAdmin($id = 0) {
        global $database, $my;
// echo '<div>CALL isModOrAdmin</div>';
        $userid = intval($id);

        if ($userid) {
            $user = new mosUser($database);
            $user->load($userid);
            }
        else {
            $user = $my;
            }

        if (strtolower($user->usertype) == 'super administrator' || strtolower($user->usertype) == 'administrator') {
            return true;
            }

            return false;
        }

    function fbMovePosts($catid, $isMod, $return) {
        global $my, $database;
	$err = "ERROR!";

	// $isMod if user is moderator in the current category
	if (!$isMod) {
		// Test also if user is a moderator in some other category
		$database->setQuery('SELECT userid FROM #__fb_moderation WHERE userid='.$my->id);
		$isMod = $database->loadResult();
		check_dberror("Unable to load moderation info.");
	}
	$isAdmin = CKunenaTools::isModOrAdmin();

        //isMod will stay until better group management comes in
        if (!$isAdmin && !$isMod) {
            mosRedirect($return, _POST_NOT_MODERATOR);
            }

		$catid = (int)$catid;
		if ($catid > 0) {
	        $items = fbGetArrayInts("fbDelete");

	        // start iterating here

	        foreach ($items as $id => $value) {
	            $id = (int)$id;

	            $database->setQuery("SELECT `subject`, `catid`, `time` AS timestamp FROM #__fb_messages WHERE `id`=" . $id);
	            $oldRecord = $database->loadObjectList();
	            	check_dberror("Unable to load message detail.");

                    $newCatObj = new jbCategory($database, $oldRecord[0]->catid);
		    if (fb_has_moderator_permission($database, $newCatObj, $my->id, $isAdmin)) {

		        $newSubject = _MOVED_TOPIC . " " . $oldRecord[0]->subject;
		        $database->setQuery("SELECT MAX(time) AS timestamp FROM #__fb_messages WHERE `thread`=" . $id);
		        $lastTimestamp = $database->loadResult();
			check_dberror("Unable to load messages max(time).");

			if ($lastTimestamp == "") {
				$lastTimestamp = $oldRecord[0]->timestamp;
                	}

			//perform the actual move
			$database->setQuery("UPDATE #__fb_messages SET `catid`='$catid' WHERE `id`='$id' OR `thread`='$id'");
			$database->query();
			check_dberror("Unable to move thread.");

			$err = _POST_SUCCESS_MOVE;
		    } else {
                        $err = _POST_NOT_MODERATOR;
                    }
		} //end foreach
		} else {
			$err = _POST_NO_DEST_CATEGORY;
		}
        CKunenaTools::reCountBoards();

        mosRedirect($return, $err);
        }

        function isJoomla15()
        {
            return (defined('_JEXEC') && class_exists('JApplication'));
        }


        function fbRemoveXSS($val, $reverse = 0) {

           // now the only remaining whitespace attacks are \t, \n, and \r
           $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
           $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
           $ra = array_merge($ra1, $ra2);

           $ra2 = $ra;
           array_walk($ra2, "fbReturnDashed");

           if ($reverse) {
                $val = str_ireplace($ra2, $ra, $val);
           }
           else {
           //replace them all with a dummy variable, and later replace them in CODE
                $val = str_ireplace($ra, $ra2, $val);
           }
           return $val;
        }

	function &prepareContent(&$content)
	{
		$fbConfig =& CKunenaConfig::getInstance();

		// Joomla Mambot Support, Thanks hacksider
		if ($fbConfig->jmambot)
		{
			$row =& new stdClass();
			$row->text =& $content;
			$params =& new mosParameters( '' );
			if (CKunenaTools::isJoomla15()) {
				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$results = $dispatcher->trigger('onPrepareContent', array (&
$row, & $params, 0));
			} else {
				global $_MAMBOTS;
				$_MAMBOTS->loadBotGroup( 'content' );
				$results = $_MAMBOTS->trigger( 'onPrepareContent', array( &$row, &$params, 0 ), true );
			}
			$content =& $row->text;
		}
		return $content;
	}

	function getAllowedForums($uid = 0, $gid = 0, &$acl) {
        	global $database;

			function _has_rights(&$acl, $gid, $access, $recurse) {
				if ($gid == $access) return 1;
				if ($recurse) {
					$childs = $acl->get_group_children($access, 'ARO', 'RECURSE');
					return (is_array($childs) and in_array($gid, $childs));
				}
				return 0;
			}

			$catlist = '';
			$query = "SELECT c.id,c.pub_access,c.pub_recurse,c.admin_access,c.admin_recurse,c.moderated"
				. ",(m.userid IS NOT NULL) AS ismod FROM #__fb_categories c"
				. " LEFT JOIN #__fb_moderation m ON c.id=m.catid AND m.userid="
				. (int)$uid . " WHERE published=1";
			$database->setQuery($query);
			$rows = $database->loadObjectList();
					check_dberror("Unable to load category list.");
			if ($rows) {
				foreach($rows as $row) {
					if (($row->moderated and $row->ismod) or
						($row->pub_access == 0) or
						($row->pub_access == -1 and $uid > 0) or
						($row->pub_access > 0 and _has_rights($acl, $gid, $row->pub_access, $row->pub_recurse)) or
						($row->admin_access > 0 and _has_rights($acl, $gid, $row->admin_access, $row->admin_recurse))
					) $catlist .= (($catlist == '')?'':',').$row->id;
				}
			}
			return $catlist;
		}

    } // end of class

/**
* Moderator Table Class
*
* Provides access to the #__fb_moderator table
*/
class fbModeration
    extends mosDBTable {
    /** @var int Unique id*/
    var $catid = null;
    /** @var int */
    var $userid = null;
    /** @var int */
    var $future1 = null;
    /** @var int */
    var $future2 = null;
    /**
    * @param database A database connector object
    */
    function fbModeration(&$database) {
        $this->mosDBTable('#__fb_moderation', 'catid', $database);
        }
    }

class fbForum
    extends mosDBTable {
    /** @var int Unique id*/
    var $id = null;
    /** @var string */
    var $parent = null;
    /** @var string */
    var $name = null;
    var $cat_emoticon = null;
    var $locked = null;
    var $alert_admin = null;
    var $moderated = null;
    var $pub_access = null;
    var $pub_recurse = null;
    var $admin_access = null;
    var $admin_recurse = null;
    var $public = null;
    var $ordering = null;
    var $future2 = null;
    var $published = null;
    var $checked_out = null;
    var $checked_out_time = null;
    var $review = null;
    var $hits = null;
    var $description = null;
    var $class_sfx = null;
    var $headerdesc = null;
    /**
    * @param database A database connector object
    */
    function fbForum(&$database) {
        $this->mosDBTable('#__fb_categories', 'id', $database);
    }

	// check for potential problems
	function check() {
		$this->_error = '';
		if ($this->parent) {
			if ($this->id == $this->parent):
				$this->setError(_KUNENA_FORUM_SAME_ERR);
			elseif ($this->isChild($this->parent)):
				$this->setError(_KUNENA_FORUM_OWNCHILD_ERR);
			endif;
		}
		return ($this->getError() == '');
	}

	// check if given forum is one of its own childs
	function isChild($id) {
		if ($id > 0) {
			$query = "SELECT id, parent FROM #__fb_categories";
			$this->_db->setQuery($query);
			$this->_db->query() or trigger_dberror("Unable to access categories.");
			$list = $this->_db->loadObjectList('id');
			$recurse = array();
			while ($id) {
				if (in_array($id, $recurse)) {
					$this->setError(_KUNENA_RECURSION);
					return 0;
				}
				$recurse[] = $id;
				if (!isset($list[$id])) {
					$this->setError(_KUNENA_FORUM_UNKNOWN_ERR);
					return 0;
				}
				$id = $list[$id]->parent;
				if ($id <> 0 and $id == $this->id)
					return 1;
			};
		}
		return 0;
	}

	function setError($msg) {
		$this->_error = ($msg <> '')?$msg:'error';
	}

	function store($updateNulls=false) {
		if ($ret = parent::store($updateNulls)) {
			// we must reset fbSession (allowed), when forum record was changed

			$this->_db->setQuery("UPDATE #__fb_sessions SET allowed='na'");
			$this->_db->query() or trigger_dberror("Unable to update sessions.");
		}
		return $ret;
	}

}

function JJ_categoryArray($admin=0) {
    global $database;
    global $aro_group;

    // get a list of the menu items
	$query = "SELECT * FROM #__fb_categories";
	if(!$admin) {
		$fbSession =& CKunenaSession::getInstance();
		if ($fbSession && $fbSession->allowed != 'na') {
			$query .= " WHERE id IN ($fbSession->allowed)";
		} else {
			$query .= " WHERE pub_access=0 AND published=1";
		}
	}
    $query .= " ORDER BY ordering, name";
    $database->setQuery($query);
    $items = $database->loadObjectList();
    	check_dberror("Unable to load categories.");
    // establish the hierarchy of the menu
    $children = array ();

    // first pass - collect children
    foreach ($items as $v) {
        $pt = $v->parent;
        $list = @$children[$pt] ? $children[$pt] : array ();
        array_push($list, $v);
        $children[$pt] = $list;
        }

    // second pass - get an indent list of the items
    $array = fbTreeRecurse(0, '', array (), $children, 10, 0, 1);
    return $array;
    }

function fbTreeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 ) {

    if (@$children[$id] && $level <= $maxlevel) {
        foreach ($children[$id] as $v) {
            $id = $v->id;
            if ( $type ) {
                $pre     = '&nbsp;';
                $spacer = '...';
            } else {
                $pre     = '- ';
                $spacer = '&nbsp;&nbsp;';
            }

            if ( $v->parent == 0 ) {
                $txt     = kunena_htmlspecialchars(stripslashes($v->name));
            } else {
                $txt     = $pre . kunena_htmlspecialchars(stripslashes($v->name));
            }
            $pt = $v->parent;
            $list[$id] = $v;
            $list[$id]->treename = $indent . $txt;
            $list[$id]->children = count( @$children[$id] );

            $list = fbTreeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
        }
    }
    return $list;
}

function JJ_categoryParentList($catid, $action, $options = array ()) {
    global $database;

    $list = JJ_categoryArray();
    $this_treename = '';

    foreach ($list as $item) {
        if ($this_treename) {
            if ($item->id != $catid && strpos($item->treename, $this_treename) === false) {
                $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            }
        else {
            if ($item->id != $catid) {
                $options[] = mosHTML::makeOption($item->id, $item->treename);
                }
            else {
                $this_treename = "$item->treename/";
                }
            }
        }

    $parent = mosHTML::selectList($options, 'catid', 'class="inputbox fbs" size="1"  onchange = "if(this.options[this.selectedIndex].value > 0){ this.form.submit() }"', 'value', 'text', $catid);
    return $parent;
    }

function KUNENA_GetAvailableForums($catid, $action, $options = array (), $disabled, $multiple = 0) {
    global $database;
    $list = JJ_categoryArray();
    $this_treename = '';

    foreach ($list as $item) {
        if ($this_treename) {
            if ($item->id != $catid && strpos($item->treename, $this_treename) === false) {
                $options[] = mosHTML::makeOption($item->id, kunena_htmlspecialchars($item->treename));
                }
            }
        else {
            if ($item->id != $catid) {
                $options[] = mosHTML::makeOption($item->id, kunena_htmlspecialchars($item->treename));
                }
            else {
                $this_treename = "$item->treename/";
                }
            }
        }

	$tag_attribs = 'class="inputbox fbs" '.($multiple?' size="5" MULTIPLE ':' size="1" ') . ($disabled ? ' disabled="disabled"' : '');
	if (CKunenaTools::isJoomla15()) {
    	$parent = JHTML::_('select.genericlist', $options, 'catid', $tag_attribs , 'value', 'text', $catid, 'KUNENA_AvailableForums');
		}
    else {
		$parent = mosHTML::selectList($options, 'catid', $tag_attribs . ' ID="KUNENA_AvailableForums"' , 'value', 'text', $catid);
		}
    return $parent;
    }

//
//Begin Smilies mod
//
function generate_smilies() {
    global $database;

    $inline_columns = 4;
    $inline_rows = 5;

    $database->setQuery("SELECT code, location, emoticonbar FROM #__fb_smileys ORDER BY id");

    if ($database->query()) {
        $num_smilies = 0;
        $rowset = array ();
        $set = $database->loadAssocList();
        $num_iconbar = 0;

        foreach ($set as $smilies) {
            $key_exists = false;

            foreach ($rowset as $check) //checks if the smiley (location) already exists with another code
            {
                if ($check['location'] == $smilies['location']) {
                    $key_exists = true;
                    }
                }

            if ($key_exists == false) {
                $rowset[] = array
                (
                    'code' => $smilies['code'],
                    'location' => $smilies['location'],
                    'emoticonbar' => $smilies['emoticonbar']
                );
                }

            if ($smilies['emoticonbar'] == 1) {
                $num_iconbar++;
                }
            }

        $num_smilies = count($rowset);

        if ($num_smilies) {
            $smilies_count = min(20, $num_smilies);
            $smilies_split_row = $inline_columns - 1;

            $s_colspan = 0;
            $row = 0;
            $col = 0;
            reset ($rowset);

            $cur = 0;

            foreach ($rowset as $data) {
                if ($data['emoticonbar'] == 1) {
                    $cur++;

                    if (!($cur > $inline_rows * $inline_columns)) {
                        if (!$col) {
                            echo '<tr align="center" valign="middle">' . "\n";
                            }

                        echo '<td onclick="bbfontstyle(\' '
                                 . $data['code'] . ' \',\'\')" style="cursor:pointer"><img class="btnImage" src="' . KUNENA_URLEMOTIONSPATH . $data['location'] . '" border="0" alt="' . $data['code'] . ' " title="' . $data['code'] . ' " /></td>' . "\n";

                        $s_colspan = max($s_colspan, $col + 1);

                        if ($col == $smilies_split_row) {
                            $col = 0;
                            $row++;
                            echo "</tr>\n";
                            }
                        elseif ($cur == $num_iconbar && $s_colspan !== 0) {
                            echo "<td colspan=\"" . $s_colspan . "\"></td></tr>";
                            }
                        else {
                            $col++;
                            }
                        }
                    }
                }

            if ($num_smilies > $inline_rows * $inline_columns) {
                echo "<tr><td class=\"moresmilies\" colspan=\"" . $inline_columns . "\" onclick=\"javascript:dE('smilie');\" style=\"cursor:pointer\"><b>" . _KUNENA_EMOTICONS_MORE_SMILIES . "</b></td></tr>";
                }
            }
        }
    }

function fbGetArrayInts($name, $type = NULL) {
    if ($type == NULL) {
        $type = $_POST;
        }

    $array = mosGetParam($type, $name, array ( 0 ));

    mosArrayToInts ($array);

    if (!is_array($array)) {
        $array = array ( 0 );
        }

    return $array;
    }

    function time_since($older_date, $newer_date)
    {
    // ToDo: return code plus string to decide concatenation.
    // array of time period chunks
    $chunks = array(
    array(60 * 60 * 24 * 365 , _KUNENA_DATE_YEAR, _KUNENA_DATE_YEARS),
    array(60 * 60 * 24 * 30 , _KUNENA_DATE_MONTH, _KUNENA_DATE_MONTHS),
    array(60 * 60 * 24 * 7, _KUNENA_DATE_WEEK, _KUNENA_DATE_WEEKS),
    array(60 * 60 * 24 , _KUNENA_DATE_DAY, _KUNENA_DATE_DAYS),
    array(60 * 60 , _KUNENA_DATE_HOUR, _KUNENA_DATE_HOURS),
    array(60 , _KUNENA_DATE_MINUTE, _KUNENA_DATE_MINUTES),
    );

    // $newer_date will equal false if we want to know the time elapsed between a date and the current time
    // $newer_date will have a value if we want to work out time elapsed between two known dates
    //$newer_date = ($newer_date == false) ? CKunenaTools::fbGetInternalTime() : $newer_date;

    // difference in seconds
    $since = $newer_date - $older_date;

    // no negatives!
    if($since<=0) {
      return '?';
    }

    // we only want to output two chunks of time here, eg:
    // x years, xx months
    // x days, xx hours
    // so there's only two bits of calculation below:

    // step one: the first chunk
    for ($i = 0, $j = count($chunks); $i < $j; $i++)
        {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        $names = $chunks[$i][2];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0)
            {
            break;
            }
        }

    // set output var
    $output = ($count == 1) ? '1 '.$name : $count.' '.$names ;

    // step two: the second chunk
    if ($i + 1 < $j)
        {
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];
        $names2 = $chunks[$i + 1][2];

        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0)
            {
            // add to output var
            $output .= ($count2 == 1) ? ', 1 '.$name2 : ', '.$count2.' '.$names2;
            }
        }

    return $output;
    }

function make_pattern(&$pat, $key) {
  $pat = '/'.preg_quote($pat, '/').'/i';
}
if (!function_exists("htmlspecialchars_decode")) {
    function htmlspecialchars_decode($string,$style=ENT_COMPAT)
    {
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if($style === ENT_QUOTES) { $translation['&#039;'] = '\''; }
        return strtr($string,$translation);
    }
}
if(!function_exists('str_ireplace')){
function str_ireplace($search, $replace, $subject){
if(is_array($search)){
array_walk($search, 'make_pattern');
}
else{
$search = '/'.preg_quote($search, '/').'/i';
}
return preg_replace($search, $replace, $subject);
}
}

function fbReturnDashed (&$string, $key) {
            $string = "_".$string."_";
}

function kn_mb_substr($str, $start, $length=NULL, $encoding=NULL) {
	if ($length === NULL) $length = strlen($str);
	if ($encoding === NULL) $encoding = KUNENA_CHARSET;
	if (!function_exists('mb_substr'))
	{
		if (CKunenaTools::isJoomla15())
		{
			require_once(JPATH_LIBRARIES.DS.'phputf8'.DS.'utf8.php');
		}
		else
		{
			return substr($str, $start, $length);
		}
	}
	return mb_substr($str, $start, $length, $encoding);
}

function kunena_htmlspecialchars($string, $quote_style=ENT_COMPAT, $charset=KUNENA_CHARSET) {
	return htmlspecialchars($string, $quote_style, $charset);
}

function utf8_urldecode($str) {
	$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x1;",urldecode($str));
	return html_entity_decode_utf8($str);
}

function html_entity_decode_utf8($string)
{
    static $trans_tbl;

    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $string);

    // replace literal entities
    if (!isset($trans_tbl))
    {
        $trans_tbl = array();

        foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
            $trans_tbl[$key] = utf8_encode($val);
    }

    return strtr($string, $trans_tbl);
}

// Returns the utf string corresponding to the unicode value (from php.net, courtesy - romans@void.lv)
function code2utf($num)
{
    if ($num < 128) return chr($num);
    if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
    if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    return '';
}

?>
