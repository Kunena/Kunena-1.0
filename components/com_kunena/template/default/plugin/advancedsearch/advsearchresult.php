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

$searchword = mosGetParam($_REQUEST, 'searchword');
$titleonly = intval(mosGetParam($_REQUEST, 'titleonly'));
$searchuser = mosGetParam($_REQUEST, 'searchuser');
$starteronly = intval(mosGetParam($_REQUEST, 'starteronly'));
$exactname = intval(mosGetParam($_REQUEST, 'exactname'));
$replyless = intval(mosGetParam($_REQUEST, 'replyless'));
$replylimit = intval(mosGetParam($_REQUEST, 'replylimit'));
$searchdate = mosGetParam($_REQUEST, 'searchdate');
$beforeafter = mosGetParam($_REQUEST, 'beforeafter');
$sortby = mosGetParam($_REQUEST, 'sortby');
$order = mosGetParam($_REQUEST, 'order');
$catid = mosGetParam($_REQUEST, 'catid');

// searchword must contain a minimum of 3 characters
if ($searchword && strlen($searchword) < 3 || strlen($searchword) == '0') {
    mosRedirect('index.php?option=com_kunena&amp;func=advsearch&amp;Itemid=' . KUNENA_COMPONENT_ITEMID);
}

$searchword = strval($searchword);
$searchword = htmlspecialchars($searchword);
$searchword = trim(stripslashes($searchword));

//connect db and get data that we want to find
$query = "SELECT * FROM #__sb_messages";
?>