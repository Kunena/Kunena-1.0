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
global $fbConfig;
?>
<!-- Pathway -->
<?php
$sfunc = mosGetParam($_REQUEST, "func", null);

if ($func != "")
{
?>

    <div class = "<?php echo $boardclass ?>forum-pathway">
        <?php
        $catids = intval($catid);
        $parent_ids = 1000;
        $jr_it = 1;
        $jr_path_menu = array ();
        $shome = '<div class="path-element-first">' . CKunenaLink::GetKunenaLink( htmlspecialchars(stripslashes($fbConfig->board_title)) );

        while ($parent_ids)
        {
            $query = "select * from #__fb_categories where id=$catids and published=1";
            $database->setQuery($query);
            $database->loadObject($results);
			$parent_ids = $results->parent;
			$fr_name = htmlspecialchars(trim(stripslashes($results->name)));
            //$cids=@mysql_result( $results, 0, 'id' );
            $sname = CKunenaLink::GetCategoryLink( 'showcat', $catids, $fr_name);

            if ($jr_it == 1 && $sfunc != "view")
            {
                $fr_title_name = $fr_name;
                $jr_path_menu[] = $fr_name;
            }
            else {
                $jr_path_menu[] = $sname;
            }

            // write path
            if (empty($spath)) {
                $spath = $sname;
            }
            else {
                $spath = $sname . "<div class=\"path-element\">" . $spath . "</div>";
            }

            // next looping
            $catids = $parent_ids;
            $jr_it++;
        }

        $jr_path_menu[] = $shome;
        //reverse the array
        $jr_path_menu = array_reverse($jr_path_menu);

        //  echo $shome." " . $jr_arrow .$jr_arrow ." ". $spath;
        //attach topic name
        if ($sfunc == "view" and $id)
        {
            $sql = "select subject from #__fb_messages where id = $id";
            $database->setQuery($sql);
            $jr_topic_title = stripslashes(htmlspecialchars($database->loadResult()));
            $jr_path_menu[] = $jr_topic_title;
        //     echo " " . $jr_arrow .$jr_arrow ." ". $jr_topic_title;
        }

        // print the list
        $jr_forum_count = count($jr_path_menu);

        for ($i = 0; $i <= (count($jr_path_menu) - 1); $i++)
        {
            if ($i > 0 && $i == $jr_forum_count - 1) {
                echo '<div class="path-element-last">';
            }
            else if ($i > 0) {
                echo '<div class="path-element">';
            }

            echo $jr_path_menu[$i] . "</div>";
        }

        if ($forumLocked)
        {
            echo $fbIcons['forumlocked'] ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['forumlocked']
                     . '" border="0" alt="' . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '"/>' : '  <img src="' . KUNENA_URLEMOTIONSPATH . 'lock.gif"  border="0"  alt="' . _GEN_LOCKED_FORUM . '" title="' . _GEN_LOCKED_FORUM . '">';
            $lockedForum = 1;
        }
        else {
            echo "";
        }

        if ($forumReviewed)
        {
            echo $fbIcons['forummoderated'] ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['forummoderated']
                     . '" border="0" alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '"/>' : '  <img src="' . KUNENA_URLEMOTIONSPATH . 'review.gif" border="0"  alt="' . _GEN_MODERATED . '" title="' . _GEN_MODERATED . '">';
            $moderatedForum = 1;
        }
        else {
            echo "";
        }

         //get viewing
        $fb_queryName = $fbConfig->username ? "username" : "name";
		$query= "SELECT w.userid, u.$fb_queryName AS username , k.showOnline FROM #__fb_whoisonline AS w LEFT JOIN #__users AS u ON u.id=w.userid LEFT JOIN #__fb_users AS k ON k.userid=w.userid  WHERE w.link like '%" . addslashes($_SERVER['REQUEST_URI']) . "%' GROUP BY w.userid ORDER BY u.$fb_queryName ASC";
		$database->setQuery($query);
		$users = $database->loadObjectList();
			check_dberror("Unable to load who is online.");
		$total_viewing = count($users);

        if ($sfunc == "userprofile")
        {
            echo _USER_PROFILE;
            echo $username;
        }
        else {
			echo "<div class=\"path-element-users\">($total_viewing " . _KUNENA_PATHWAY_VIEWING . ")&nbsp;";
			$totalguest = 0;
			$lastone = end($users);
			foreach ($users as $user) {
				if ($user->userid != 0)
				{
					if($user==$lastone && !$totalguest){ 
					$divider = '';
					}
					if ( $user->showOnline > 0 ){
					echo CKunenaLink::GetProfileLink($fbConfig,  $user->userid, $user->username) . $divider.' ';
					}
				}
				else
				{
					$totalguest = $totalguest + 1;
				}
			}
      if ($totalguest > 0) { if ($totalguest==1) { echo $totalguest.'&nbsp;'._WHO_ONLINE_GUEST; } else { echo '('.$totalguest.') '._WHO_ONLINE_GUESTS; } }
       }

        unset($shome, $spath, $parent_ids, $catids, $results, $sname);
	$fr_title = $fr_title_name . $jr_topic_title;
        $mainframe->setPageTitle(($fr_title ? $fr_title : _KUNENA_CATEGORIES) . ' - ' . stripslashes($fbConfig->board_title));
        ?>
		</div>
    </div>

<?php
}
?>
<!-- / Pathway -->
