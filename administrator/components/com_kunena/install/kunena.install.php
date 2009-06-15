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
//
// Dont allow direct linking
defined ('_VALID_MOS') or die('Direct Access to this location is not allowed.');

// Help get past php timeouts if we made it that far
// Joomla 1.5 installer can be very slow and this helps avoid timeouts
set_time_limit(300);
ini_set("memory_limit", "32M");

// Minimum version requirements
DEFINE('KUNENA_MIN_PHP', '4.3.9');
DEFINE('KUNENA_MIN_MYSQL', '4.1.19');

global $mainframe;
//Get right Language file
if (file_exists($mainframe->getCfg('absolute_path') . '/administrator/components/com_kunena/language/kunena.' . $mainframe->getCfg('lang') . '.php')) {
    include ($mainframe->getCfg('absolute_path') . '/administrator/components/com_kunena/language/kunena.' . $mainframe->getCfg('lang') . '.php');
}
else {
    include ($mainframe->getCfg('absolute_path') . '/administrator/components/com_kunena/language/kunena.english.php');
}

include_once($mainframe->getCfg("absolute_path")."/administrator/components/com_kunena/lib/fx.upgrade.class.php");

function com_install() {
	global $database, $mainframe, $mosConfig_absolute_path;

	// Determine MySQL version from phpinfo
	$database->setQuery("SELECT VERSION() as mysql_version");
	$mysqlversion = $database->loadResult();

	//before we do anything else we want to check for minimum system requirements
	if (version_compare(phpversion(), KUNENA_MIN_PHP, ">=") && version_compare($mysqlversion, KUNENA_MIN_MYSQL, ">"))
	{
		// we're on 4.3.0 or later

		//change fb menu icon
		$database->setQuery("SELECT id FROM #__components WHERE admin_menu_link = 'option=com_kunena'");
		$id = $database->loadResult();

		//add new admin menu images
		if(defined('_JEXEC') && class_exists('JApplication'))
		{
			// J1.5
			$database->setQuery("UPDATE #__components SET admin_menu_img  = 'components/com_kunena/images/kunenafavicon.png'" . ",   admin_menu_link = 'option=com_kunena' " . "WHERE id='".$id."'");
		}
		else
		{
			// J1.0
			$database->setQuery("UPDATE #__components SET admin_menu_img  = '../administrator/components/com_kunena/images/kunenafavicon.png'" . ",   admin_menu_link = 'option=com_kunena' " . "WHERE id='".$id."'");
		}
		$database->query() or trigger_dbwarning("Unable to set admin menu image.");

		//install & upgrade class
		$fbupgrade = new fx_Upgrade("com_kunena", "kunena.install.upgrade.xml", "fb_", "install", false);

		// Legacy enabler
		// Versions prior to 1.0.5 did not came with a version table inside the database
		// this would make the installer believe this is a fresh install. We need to perform
		// a 'manual' check if this is going to be an upgrade and if so create that table
		// and write a dummy version entry to force an upgrade.

		$database->setQuery( "SHOW TABLES LIKE '%fb_messages'" );
		$database->query() or trigger_dbwarning("Unable to search for messages table.");

		if($database->getNumRows()) {
			// fb tables exist, now lets see if we have a version table
			$database->setQuery( "SHOW TABLES LIKE '%fb_version'" );
			$database->query() or trigger_dbwarning("Unable to search for version table.");;
			if(!$database->getNumRows()) {
				//version table does not exist - this is a pre 1.0.5 install - lets create
				$fbupgrade->createVersionTable();
				// insert dummy version entry to force upgrade
				$fbupgrade->insertDummyVersion();
			}
		}
		// Start Installation/Upgrade
		$fbupgrade->doUpgrade();

		// THIS PROCEDURE IS UNTRANSLATED!
	?>

<style>
.fbscs {
	margin: 0;
	padding: 0;
	list-style: none;
}

.fbscslist {
	list-style: none;
	padding: 5px 10px;
	margin: 3px 0;
	border: 1px solid #66CC66;
	background: #D6FEB8;
	display: block;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333;
}

.fbscslisterror {
	list-style: none;
	padding: 5px 10px;
	margin: 3px 0;
	border: 1px solid #FF9999;
	background: #FFCCCC;
	display: block;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333;
}
</style>

<div style="border: 1px solid #ccc; background: #FBFBFB; padding: 10px; text-align: left; margin: 10px 0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20%" valign="top" style="padding: 10px;"><a
			href="index2.php?option=com_kunena"><img
			src="components/com_kunena/images/kunena.logo.png" alt="Kunena"
			border="0"></a></td>

		<td width="80%" valign="top" style="padding: 10px;">
		<div style="clear: both; text-align: left; padding: 0 20px;">
		<ul class="fbscs">
		<?php

			//
			// We might want to make the file copy below part of the install as well
			//
			$success = false;
			if (is_writable($mainframe->getCfg("absolute_path")."/images" ))
			{
				//ok now it is installed, just copy the fbfiles directory, and apply 0777
				$success = dircopy($mainframe->getCfg("absolute_path") . "/components/com_kunena/kunena.files.distribution", $mainframe->getCfg("absolute_path") . "/images/fbfiles", false);
			}
			if ($success === false) {
			?>

			<li class="fbscslisterror">
			<div
				style="border: 1px solid #FF6666; background: #FFCC99; padding: 10px; text-align: left; margin: 10px 0;">
			<img src='images/publish_x.png' align='absmiddle' />
			Creation or permission setting of the following directories failed: <br />
			<pre> <?php echo $mainframe->getCfg("absolute_path"); ?>/images/fbfiles/
			<?php echo $mainframe->getCfg("absolute_path");?>/images/fbfiles/avatars
			<?php echo $mainframe->getCfg("absolute_path");?>/images/fbfiles/avatars/gallery (you have to put avatars inside if you want to use it)
			<?php echo $mainframe->getCfg("absolute_path");?>/images/fbfiles/category_images
			<?php echo $mainframe->getCfg("absolute_path");?>/images/fbfiles/files
			<?php echo $mainframe->getCfg("absolute_path");?>/images/fbfiles/images
</pre> a) You can copy the contents of _kunena.files.distribution under
			components/com_kunena to your Joomla root, under images/ folder.

			<br />
			b) If you already have the contents there, but Kunena installation
			was not able to make them writable, then please do it manually.</div>

			</li>

			<?php
			}
		?>
		</ul>
		</div>

		<div
			style="border: 1px solid #FFCC99; background: #FFFFCC; padding: 20px; margin: 20px; clear: both;">
		<strong>I N S T A L L : <font color="green">Successful</font> </strong>
		<br />
		<br />
		<strong>php version: <font color="green"><?php echo phpversion(); ?></font> (Required &gt;= <?php echo KUNENA_MIN_PHP; ?>)</strong>
		<br />
		<strong>mysql version: <font color="green"><?php echo $mysqlversion; ?></font> (Required &gt; <?php echo KUNENA_MIN_MYSQL; ?>)</strong>
		</div>

		<?php
	}
	else
	{
		// Minimum version requirements not satisfied
		?>
<style>
.fbscs {
	margin: 0;
	padding: 0;
	list-style: none;
}

.fbscslist {
	list-style: none;
	padding: 5px 10px;
	margin: 3px 0;
	border: 1px solid #66CC66;
	background: #D6FEB8;
	display: block;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333;
}

.fbscslisterror {
	list-style: none;
	padding: 5px 10px;
	margin: 3px 0;
	border: 1px solid #FF9999;
	background: #FFCCCC;
	display: block;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333;
}
</style>

<div style="border: 1px solid #ccc; background: #FBFBFB; padding: 10px; text-align: left; margin: 10px 0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="20%" valign="top" style="padding: 10px;"><a
			href="index2.php?option=com_kunena"><img
			src="components/com_kunena/images/kunena.logo.png" alt="Kunena"
			border="0"></a></td>

		<td width="80%" valign="top" style="padding: 10px;">

		<div
			style="border: 1px solid #FFCC99; background: #FFFFCC; padding: 20px; margin: 20px; clear: both;">
		<strong>I N S T A L L : <font color="red">F A I L E D - Minimum Version Requirements not satisfied</font> </strong>
		<br />
		<br />
		<strong>php version: <font color="<?php echo version_compare(phpversion(), KUNENA_MIN_PHP, '>=')?'green':'red'; ?>"><?php echo phpversion(); ?></font> (Required &gt;= <?php echo KUNENA_MIN_PHP; ?>)</strong>
		<br />
		<strong>mysql version: <font color="<?php echo version_compare($mysqlversion, KUNENA_MIN_MYSQL, '>')?'green':'red'; ?>"><?php echo $mysqlversion; ?></font> (Required &gt; <?php echo KUNENA_MIN_MYSQL; ?>)</strong>
		</div>

		<?php
	}

	// Rest of footer
	?>
		<div
			style="border: 1px solid #99CCFF; background: #D9D9FF; padding: 20px; margin: 20px; clear: both;">
		<strong>Thank you for using Kunena!</strong> <br />

		Kunena Forum Component <em>for Joomla! </em> &copy; by <a
			href="http://www.Kunena.com" target="_blank">www.Kunena.com</a>.
		All rights reserved.</div>
		</td>
	</tr>
</table>
</div>
	<?php

}

function dircopy($srcdir, $dstdir, $verbose = true) {
	$success = true;
	$num=0;

	if (!is_dir($dstdir)) {
		mkdir ($dstdir);
	}

	if ($curdir = opendir($srcdir)) {
		while ($file = readdir($curdir)) {
			if ($file != '.' && $file != '..') {
				$srcfile = $srcdir . '/' . $file;
				$dstfile = $dstdir . '/' . $file;

				if (is_file($srcfile)) {
					if (is_file($dstfile)) {
						$ow = filemtime($srcfile) - filemtime($dstfile);
					}
					else {
						$ow = 1;
					}

					if ($ow > 0) {
						if ($verbose) {
							$tmpstr = _KUNENA_COPY_FILE;
							$tmpstr = str_replace('%src%', $srcfile, $tmpstr);
							$tmpstr = str_replace('%dst%', $dstfile, $tmpstr);
							echo "<li class=\"fbscslist\">".$tmpstr;
						}

						if (@copy($srcfile, $dstfile)) {
							touch($dstfile, filemtime($srcfile));
							$num++;

							if ($verbose) {
								echo _KUNENA_COPY_OK." </li>";
							}
						}
						else {
							echo "<li class=\"fbscslisterror\">"._KUNENA_DIRCOPERR . " '$srcfile' " . _KUNENA_DIRCOPERR1."</li>";
							$success = false;
						}
					}
				}
				else if (is_dir($srcfile)) {
					$tmpret = dircopy($srcfile, $dstfile, $verbose);
					if ($tmpret === false) $success = false;
				}
			}
		}

		closedir ($curdir);
	}

	return $success;
}
?>
