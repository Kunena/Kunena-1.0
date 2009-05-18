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
// ################################################################
// MOS Intruder Alerts
defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

// ################################################################
class CKunenaToolbar
{
    function _ADMIN()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::publish();
        mosMenuBar::spacer();
        mosMenuBar::unpublish();
        mosMenuBar::spacer();
        mosMenuBar::addNew();
        mosMenuBar::spacer();
        mosMenuBar::editList();
        mosMenuBar::spacer();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _EDIT()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save();
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::unpublish('removemoderator');
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _NEWMOD_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::publish('addmoderator');
        mosMenuBar::spacer();
        mosMenuBar::unpublish('removemoderator');
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _EDIT_CONFIG()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('saveconfig');
        mosMenuBar::spacer();
        mosMenuBar::back();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _EDITUSER_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('saveuserprofile');
        mosMenuBar::spacer();
        mosMenuBar::cancel('showprofiles', _KUNENA_BACK);
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _PROFILE_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::custom('userprofile', 'edit.png', 'edit_f2.png', _KUNENA_EDIT);
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::back();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function CSS_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('saveeditcss');
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _PRUNEFORUM_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::spacer();
        mosMenuBar::custom('doprune', 'delete.png', 'delete_f2.png', _KUNENA_PRUNE, false);
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function _SYNCUSERS_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::custom('dousersync', 'delete.png', 'delete_f2.png', _KUNENA_SYNC, false);
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

    function BACKONLY_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::back();
        mosMenuBar::endTable();
    }

    function DEFAULT_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::endTable();
    }

	function _SHOWSMILEY_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::addNew('newsmiley', _KUNENA_NEW_SMILIE);
        mosMenuBar::spacer();
        mosMenuBar::custom('editsmiley', 'edit.png', 'edit_f2.png', _KUNENA_EDIT);
        mosMenuBar::spacer();
        mosMenuBar::custom('deletesmiley', 'delete.png', 'delete_f2.png', _GEN_DELETE);
        mosMenuBar::spacer();
        mosMenuBar::back();
        mosMenuBar::endTable();
    }

    function _EDITSMILEY_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('savesmiley');
        mosMenuBar::spacer();
        mosMenuBar::cancel('showsmilies');
        mosMenuBar::endTable();
    }

    function _NEWSMILEY_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('savesmiley');
        mosMenuBar::spacer();
        mosMenuBar::cancel('showsmilies');
        mosMenuBar::endTable();
    }

	function _SHOWRANKS_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::addNew('newRank', _KUNENA_NEW_RANK);
        mosMenuBar::spacer();
        mosMenuBar::custom('editRank', 'edit.png', 'edit_f2.png', _KUNENA_EDIT);
        mosMenuBar::spacer();
        mosMenuBar::custom('deleteRank', 'delete.png', 'delete_f2.png', _GEN_DELETE);
        mosMenuBar::spacer();
        mosMenuBar::back();
        mosMenuBar::endTable();
    }

	function _EDITRANK_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('saveRank');
        mosMenuBar::spacer();
        mosMenuBar::cancel('ranks');
        mosMenuBar::endTable();
    }

    function _NEWRANK_MENU()
    {
        mosMenuBar::startTable();
        mosMenuBar::spacer();
        mosMenuBar::save('saveRank');
        mosMenuBar::spacer();
        mosMenuBar::cancel('ranks');
        mosMenuBar::endTable();
    }

}
?>