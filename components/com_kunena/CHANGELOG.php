<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2009 www.kunena.com All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
?>

Changelog
------------
This is a non-exhaustive (but still near complete) changelog for
the Kunena 1.x, including beta and release candidate versions.
The Kunena 1.x is based on the FireBoard releases but includes some
drastic technical changes.
Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

19-Februray-2009 Matias
# Search: Fixed SEF/no SEF issues. Pagination and links should now work for all
# Pathway: comma (,) was missing between usernames if there were no guests
# Thread View: Minor fix in pagination

18-Februray-2009 Matias
# Broke PHP4 compability, added emulation for htmlspecialchars_decode()
# Pathway: removed multiple instances of one user, use alphabetical order

Kunena 1.0.8

17-Februray-2009 fxstein
# Missing category check added to default_ex Recent Discussions tab
  Backend Show Category setting in Recent Posts can now limit the categories displayed
+ Added category id to display in backend forum administration
# Integration dependent myprofile page fixes
- Remove broken "Close all tags" when writing the message in all places
+ Installer upgrade added for recent posts categories setting
# minor naming change in 1.0.8 upgrade procedure

17-Februray-2009 Matias
# Strip extra slashes in preview
# Regression: Quick Reply Cancel button does not work
# Backend: you removing user's avatar dind't work
# My Profile: Wrong click here text after uploading avatar

16-Februray-2009 fxstein
# Fix the fix - url tags now have http added only when needed but then for sure
# jquery Cookie error: Prevent JomSocial from loading their jquery library

16-February-2009 Matias
# Fix broken link in "Mark all forums read"
# Regression: Moderator tools in showcat didn't work
# Changed all "Hacking attempt!" messages to be less radical and not to die().
# Regression: Unsticky and Unlock didn't work
^ Changed behavior of Mark all forums read, Mark forum read - no more extra screen
^ Changed behavior of Subscribe, Favorite, Sticky, Lock - no more extra screen
# Fixed broken layout in FireFox 2

15-February-2009 Matias
^ Change time formating in announcements
# Regression: Removed &#32 ; from report emails
# Fixed report URLs
# Regression: Typos in template exists checks
# Regression: Missing css class in default template
# Removed extra slashes in headerdesc, moved it to the right place in showcat
^ Tweaks in css, fix dark themes
# Missing define for _POST_NO_FAVORITED_TOPIC in kunena.english.php
# Show user only once in pathway
# Fix broken search pagination
# Fix Search contents

15-Februray-2009 fxstein
# Proper favicon in menu for Joomla 1.0.x
+ Add missing user sync language string to language file
- load and remove sample data images removed as functionality has been depriciated
# Regression: Proper Joomla 1.5 vs 1.0 detection during install
^ Backend Kunena Information updated
^ Initial base for new 3rd party profile integration framework
- Removal of legacy CB integration for profile fields. New functionality
  through plugin for all 3rd party profile providers
# Missing http:// on url codes for url that do not start with www

14-Februray-2009 fxstein
# Added missing SEF call to mark all forums read button
+ Replace com_fireboard with com_kunena in all messages and signatures
^ Preview button label now with capital 'P'
# Incorrect button css classes on write message screen fixed
^ Extra spacing for text buttons to conform with Joomla button style
# Regression: Disabled the submit button because of incorrect type - fixed
# Regression: Moderator tools got disabled during relocation - fixed
+ Missing search icon in default_ex userlist added
+ Missing css styling added to forum header description
^ Forumjump: put Go button to the right side of the drop down category list

14-February-2009 Matias
# Try 2: Use default_ex template if current template is missing
^ Changed Quick Reply icon.
^ Use the same style in all buttons. CSS simplifications, fixes

13-Februray-2009 fxstein
# Minor bug fix in automatic upgrade that re-ran 1.0.6 portion unneccesarily

13-February-2009 Matias
# Regression in r381: New pathway was slightly broken, also some css was missing
# Fixed sender in all emails. It's now "BOARD_TITLE Forum"

12-Februray-2009 fxstein
^ TOOLBAR_simpleBoard renamed to CKunenaToolbar
- Legacy FB sample data code removed
+ New Kunena sample data added for new installs

12-February-2009 Riba
^ Pathway: Removed hardcoded styling
^ Pathway: Edited html output and CSS styles for easier customization
# Pathway: Removed comma separator after last user

12-February-2009 Noel Hunter
# Changes to icons in default_ex for transparency, visibility

12-February-2009 Matias
^ Improved pagination in latestx
^ Improved pagination in showcat
^ Improved pagination in view
+ Added pathway to the bottom of the showcat page
+ Added pathway to the bottom of the view page
^ Improved looks of the showcat page
^ Improved looks of the listcat page
^ Improved looks of the view page
# Missing addslashes for signature in admin.kunena.php
# Regression in r362: Broke UTF-8 letters in many places
^ Moved Thread specific moderator tools from message contents to action list

11-February-2009 fxstein
# fixed and rename various Joomla module positions for Kunena:
  kunena_profilebox, kunena_announcement, kunena_bottom
  in addtion to the previously changed kunena_msg_1 ... n
+ Increase php timepout and memory setting once Kunena install starts
# updated database error handling for upgrade base class
# minor language file cleanup, removed none ascii characters
+ additional language strings for initial board setup on fresh install

11-February-2009 Matias
# default: No menu entry pointed to Categories if default Kunena page wasn't Categories
# Huge amount of missing slashes added and extra slashes removed from templates
# Fixed broken timed redirects

10-February-2009 fxstein
# Incorrect error message on version table creation for new installs

10-February-2009 Matias
# Regression in r338: Broke My Profile
# Regression in r338: Broke FB Profile
# Regression in r246: Broke Quick Reply for UTF-8 strings
^ Show Topic in Quick Reply
# Do not add smiley if it is attached to a letter, for example TV:s, TV:seen

9-February-2009 fxstein
# Broken RSS feed in Joomla 1.0.x fixed
^ FBTools Changed to CKunenaTools
# Updated README
# Regression: Accidentially modified MyPMSTools::getProfileLink parameters

9-February-2009 Noel Hunter
# Significant leading and trailing spaces in language file replaced with
  &#32; to avoid inadvertant omission in translation

9-February-2009 severdia
# English: Spelling & grammar corrected
# README.txt: Spelling & grammar corrected

9-February-2009 Matias
^ Changed email notification sent to subscribed users
^ Changed email notification sent to moderators
^ Changed email when someone reports message to moderators
# Topic was slightly broken in default_ex (moved, unregistered)
^ Shadow message (MOVED) will now have moderator as its author
# Regression: moving messages in viewcat didn't work for admins
# New user gets PHP warning
# No ordering for child boards

8-February-2009 fxstein
+ Community Builder 1.2 basic integration
+ Make images clickable and enable lightbox/slimbox if present in template
^ changed $obj_KUNENA_search to $KunenaSearch to match new naming convention
^ clickable images and lightboxes only on non nested images; images within URL
  codes link to the URL specified
^ fb_1 module position renamed to kunena_profilebox to match new module position naming
# Avoid forum crash when JomSocial is selected in config but not installed on system

8-February-2009 Matias
# Image and file attachments should now work in Windows too
# Fix error when deleting message(s) with missing attachment files
# Fix error when deleting message(s) written by anonymous user
# Regression: fixed an old bbCode bug again..
# Fixed error in search when there are no categories

7-February-2009 Matias
# Moderators can now move messages outside their own area (no more Hacking Attempt!)
# Remove users name and email address from every message in the view (Quick Reply)
# Fix "Post a new message" form when email is mandatory
# Allow messages to be sent even if user has no email address
# Require email address setting wasn't enforced when you posted a message

6-February-2009 fxstein
+ additional jomSocial CSS integration for better looking PM windows
^ $fbversion is now $KunenaDbVersion
+ additional db check in class.kunena.php
+ basic version info on credits page
+ enhanced version info including php and mysql on debug screen
# added default values for various user fields in backend save function
# fix broken viewtypes during upgrade and reset to flat
# modified logic to detect Kunena user profiles to avoid forum crash in rare cases
# remove avatar update from backend save to avoid user profile corruption
^ Search class renamed to CKunenaSearch
- Removed depriciated threaded view option from forum tools menu

6-February-2009 Matias
# Use meaningful page titles, add missing page titles
^ Small fixes to CSS
# Regression, done this again: Removed all short tags: < ?=

5-February-2009 Matias
# Try 2: Work around IE bug which prevented jump to last message
# Removed odd number that was sometimes showing up
^ Added Kunena Copyright to all php files

4-February-2009 Noel Hunter
^ Changes to colors in kunena.forum.css to prevent inheritance of colors
  from joomla templates making text unreadable
^ Changes to kunena.forum.css to expand whos-online in pathway for
  longer lists, reduce line height, additional color fixes
^ Remove centering from code tags in parser, to fix ie bug

4-February-2009 fxstein
^ font size regression fix: reply counts in default_ex back to x-large
^ New ad module position logic. Much Simplified with support for n module positions: kunena_msg_1
  through kunena_msg_n. n being the number of posts per page.

4-February-2009 Matias
+ First version of CKunenaUser(s) class
# Backend, User Profile: include path fixed
^ Backend, User Profile: Removed bbcode, it didn't work
^ Removed flat/threaded setting, it wasn't used
# Backend, Ranks: fixed bug when you had no ranks
# You may now have more than one announcement moderator
# Removed all short tags: < ?=
# Fixed My Profile / Forum Settings / Look and Layout

3-February-2009 fxstein
# Reverse sort bug fix. Newest messages first now work in threads.
# Minor regression and syntax fixes
# Correct last message link when reverse order is selected by the user

2-February-2009 Noel Hunter
^ Change all references from forum.css to kunena.forum.css
+ If kunena.forum.css is present in the current Joomla template css directory,
  load it instead of Kunena template's kunena.forum.css
^ Change font sizes in kunena.forum.css for default_ex from px to relative sizes (small, medium, etc)
^ Change names in for forum tools in kunena.forum.css from fireboard to kunena, add z-index:2 to menu
^ Fix css typos for forum tools menu, add z-index
- Removed unused group styles from kunena.forum.css, and associated images files from default_ex images

2-February-2009 Matias
^ Move forced width from message text to [code] tag
^ Remove confusing link from avatar upload
^ default_ex: Update latestx redirect to use CKunenaLink class

2-February-2009 fxstein
^ Removed addition left over HTML tags and text for prior threaded view support in profile
# htmlspecialchars_decode on 301 redirects to remove &amps from getting into the browser URL
^ fb_Config class changed to CKunenaConfig, boj_Config class changed to CKunenaConfigBase
+ new CKunenaConfig class functionality to support user specific settings
^ kunena_authetication changed to CKunenaAuthentication

1-February-2009 Noel Hunter
^ Use default_ex if current template is missing
+ Add title tags to reply and other buttons in "default" template
^ Work around ie bug which prevented jump to last message

1-February-2009 Matias
# xhtml fixes
# My Messages will redirect to Last Messages if user has logged out
# Regression: Fix broken icon in Joomla Backend

31-January-2009 fxstein
^ default_ex jscript and image cleanup

31-January-2009 Matias
# Additional BBCode fixes

30-January-2009 fxstein
# Additional jQuery fixes
- Removed outdated jquery.chili 1.9 libraries (different file structure)
+ Added new jquery.chili 2.2 libraries
^ Moved jquery.chili jscripts to load at the bottom of the page for faster pageloads
+ add jomSocial css in header when integration is on to enable floating PM window

30-January-2009 Matias
# Regression: favorite star didn't usually show up
+ default_ex: Added grey favorite icon for other peoples favorite threads

29-January-2009 fxstein
# Fixed incorrect MyProfile link logic with various integration options
- Removed unsusable threaded view option

29-January-2009 Matias
# Regression: Backend won't be translated

28-January-2009 fxstein
# Fixed broken display with wide code
# Fixed jQuery conflicts caused by $() usage
+ PHP and MYSQL version checks during install

28-January-2009 Matias
# Replace all occurences of jos_fb_ with #__fb_
# Don't allow anonymous users to subscribe/favorite
# Do not send email on new post if the category is moderated
# Fix broken tables fb_favorites and fb_subscriptions
# Regression from Kunena 1.0.7b: avatar upload page internal error
# Avatar upload was broken if you didn't use profile integration
# default_ex: My Profile internal link was wrong

27-January-2009 fxstein
# BBCode fix for legacy [code:1] support

Kunena 1.0.7 beta

26-January-2009 fxstein
+ JomSocial userlist integration for Kunena userlist link in front stats
- Remove old unused legacy code
^ Fixed broken PDF display
^ Corrected upgrade logic order

26-January-2009 Matias
# default_ex: Link to first unread message was sometimes broken
^ view: Message is marked new only if thread hasn't been read
+ kunena.credits.php: Added myself
# Stats should work again (typos fixed)
* My Profile: My Avatar didn't have security check for anonymous users

25-January-2009 fxstein
+ Basic JomSocial Integration
^ updated jquery to latest 1.3.1 minimized
^ fb_link class changes to CKunenaLinks
# Minor typo in include paths fixed
^ kunena.credits.php: Updated credits page
^ Various links updated
+ Kunena logos added to default and default_ex tamplates
# smile.class.php: parser references fixed

25-January-2009 Matias
# Stats: Visible even if they were disabled
# Stats: Wrong count in topics and messages
# Stats: Today/yesterday stats didn't include messages between 23:59
  and 00:01.
^ Stats: Optimized SQL queries for speed and saved 11-20 queries
! DATABASE UPDATED: new keys added to fb_messages and fb_users
# Emoticons: Broken "more emoticons" pop up in IE7.
# Forum Tools: Fixed CSS rules in default_ex
^ Anonymous user cannot be admin, saves many SQL queries
# Removing moved thread (or written by anonymous user) didn't
  work in showcat
+ view: Make new messages visible (green topic icon).
+ default_ex: Show number of new messages (just like in category view).
+ default_ex: Jump to first new message by clicking new message indicator.
! Current behaviour is "first message after logout or mark all forums read".
^ showcat, latestx: Use faster query to find all messages in a thread.
# Message posted notification page redirects after you click a link

24-January-2009 Matias
# Fixed over 100 xhtml bugs
^ No default size for [img]
^ Category parent list: jump to Board Categories with "Go" button
^ Forum stats show users in alphabetical order

01-January-2009 fxstein
+ Initial fork from FireBoard 1.0.5RC3

