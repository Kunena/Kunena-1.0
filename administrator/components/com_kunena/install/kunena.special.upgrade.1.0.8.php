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
* Kunena Upgrade file for 1.0.8
* component: com_kunena
**/

defined ('_VALID_MOS') or die('Direct Access to this location is not allowed.');

global $mainframe;

// Add custom upgrade code here
// Most or all sql statements should be covered within comupgrade.xml

$temporary = 1;
$database->setQuery("CREATE TEMPORARY TABLE #__fb_temp SELECT thread, userid FROM #__fb_favorites WHERE userid>0 GROUP BY thread, userid");
if ($database->query() == FALSE) {
	$temporary=0;
	trigger_dbwarning("Unable to fix fb_favorites table. All Favorites will be removed.");
}
$database->setQuery("TRUNCATE #__fb_favorites");
$database->query();
$database->setQuery("ALTER TABLE `#__fb_favorites` DROP INDEX `thread`, ADD UNIQUE `thread`(`thread`,`userid`)");
$database->query() or trigger_dberror("Unable to alter fb_favorites table, please contact Kunena team at www.kunena.com!");
if ($temporary) {
	$database->setQuery("INSERT INTO #__fb_favorites (thread,userid) SELECT thread, userid FROM #__fb_temp");
	$database->query() or trigger_dbwarning("Unable to fix fb_favorites table. All Favorites will be removed.");
	$database->setQuery("DROP TEMPORARY TABLE #__fb_temp");
	$database->query(); // Temporary table will go away, no check needed.
}

$temporary = 1;
$database->setQuery("CREATE TEMPORARY TABLE #__fb_temp SELECT thread, userid, future1 FROM #__fb_subscriptions WHERE userid>0 GROUP BY thread, userid");
if ($database->query() == FALSE) {
	$temporary=0;
	trigger_dbwarning("Unable to fix fb_subscriptions table. All Subscriptions will be removed.");
}
$database->setQuery("TRUNCATE #__fb_subscriptions");
$database->query();
$database->setQuery("ALTER TABLE `#__fb_subscriptions` DROP INDEX `thread`, ADD UNIQUE `thread`(`thread`,`userid`)");
$database->query() or trigger_dberror("Unable to alter fb_subscriptions table, please contact Kunena team at www.kunena.com!");
if ($temporary) {
	$database->setQuery("INSERT INTO #__fb_subscriptions (thread,userid,future1) SELECT thread, userid, future1 FROM #__fb_temp");
	$database->query() or trigger_dbwarning("Unable to fix fb_subscriptions table. All Subscriptions will be removed.");
	$database->setQuery("DROP TEMPORARY TABLE #__fb_temp");
	$database->query(); // Temporary table will go away, no check needed.
}

?>
