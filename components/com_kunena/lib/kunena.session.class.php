<?php
/**
* @version $Id: kunena.search.class.php 661 2009-05-01 08:28:21Z mahagr $
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2008 - 2009 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
*
**/
// Dont allow direct linking
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

global $database;

class CKunenaSession extends mosDBTable
{
	var $userid = 0;
	var $allowed = 'na';
	var $lasttime = 0;
	var $readtopics = '';
	var $currvisit = 0;
	var $_exists = false;

	function CKunenaSession($database)
	{
		$this->mosDBTable('#__fb_sessions', 'userid', $database);
		$this->lasttime = time() + KUNENA_OFFSET_BOARD - KUNENA_SECONDS_IN_YEAR;
		$this->currvisit = time() + KUNENA_OFFSET_BOARD;
	}

	function &getInstance()
	{
		global $database, $my;
		static $instance;
		if (!$instance) {
			$instance = new CKunenaSession($database);
			$instance->load($my->id);
		}
		return $instance;
	}

	function load( $oid=null )
	{
		$ret = parent::load($oid);
		if ($ret === true) $this->_exists = true;
		return $ret;
	}

	function store( $updateNulls=false )
	{
		$k = $this->_tbl_key;
		
		if( $this->$k && $this->_exists === true )
		{
			$ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, $updateNulls );
		}
		else
		{
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		}
		if( !$ret )
		{
			if (CKunenaTools::isJoomla15())
				$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			else
				$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
			return false;
		}
		else
		{
			return true;
		}
	}
}

?>
