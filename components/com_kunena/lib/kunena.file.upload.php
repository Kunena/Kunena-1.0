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
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once(KUNENA_ABSSOURCESPATH . 'kunena.helpers.php');

function fileUploadError($msg)
{
    global $message;
    $GLOBALS['KUNENA_rc'] = 0;
    $message = str_replace("[file]", "", $message);
    fbAlert("$msg\n" . _FILE_NOT_UPLOADED);
}

$GLOBALS['KUNENA_rc'] = 1; //reset return code
$filename = split("\.", $_FILES['attachfile']['name']);
//some transaltions for readability
//numExtensions= people tend to upload malicious files using mutliple extensions like: virus.txt.vbs; we'll want to have the last extension to validate against..
$numExtensions = (count($filename)) - 1;
//Translate all invalid characters
$fileName = preg_replace("/[^0-9a-zA-Z_]/", "_", $filename[0]);
// get the final extension
$fileExt = $filename[$numExtensions];
// create the new filename
$newFileName = $fileName . '.' . $fileExt;
// Get the Filesize
$fileSize = $_FILES['attachfile']['size'];

//Enforce it is a new file
if (file_exists(KUNENA_ABSUPLOADEDPATH. "/files/$newFileName")) {
    $newFileName = $fileName . '-' . md5(microtime()) . "." . $fileExt;
}

if ($GLOBALS['KUNENA_rc'])
{
    //Filename + proper path
    $fileLocation = strtr(KUNENA_ABSUPLOADEDPATH . "/files/$newFileName", "\\", "/");

    // Check for empty filename
    if (empty($_FILES['attachfile']['name'])) {
        fileUploadError(_FILE_ERROR_EMPTY);
    }

    // check for allowed file types
    $allowedArray = explode(',', strtolower($fbConfig->filetypes));

    if (!in_array($fileExt, $allowedArray)) {
        fileUploadError(_FILE_ERROR_TYPE . " " . $fbConfig->filetypes);
    }

    // Check filesize
    $maxImgSize = $fbConfig->filesize * 1024;

    if ($fileSize > $maxImgSize) {
        fileUploadError(_FILE_ERROR_SIZE . " (" . $fbConfig->filesize . "kb)");
    }
}

if ($GLOBALS['KUNENA_rc'])
{
	// file is OK, move it to the proper location
	if( CKunenaTools::isJoomla15() ) {
		jimport('joomla.filesystem.file');
		JFile::upload($_FILES['attachfile']['tmp_name'], $fileLocation);
	} else {
		move_uploaded_file($_FILES['attachfile']['tmp_name'], $fileLocation);
	}
}

// Insert file code into message
if ($GLOBALS['KUNENA_rc'])
{
    $code
        = '[file name='
        . $newFileName . ' size=' . $fileSize . ']' . KUNENA_LIVEUPLOADEDPATH . '/files/' . $newFileName . '[/file]';

    if (preg_match("/\[file\]/si", $message)) {
        $message = str_replace("[file]", $code, $message);
    }
    else {
        $message = $message . ' ' . $code;
    }
}
?>
