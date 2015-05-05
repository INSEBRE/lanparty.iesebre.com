<?php 
if(defined('_JEXEC')) 
	define('_VALID_MOS',1);

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');
if(defined('_JEXEC'))
		{
		define("M4J_ABS", JPATH_ROOT);
		if(!defined('_JLEGACY')) require_once(M4J_ABS . '/administrator/components/com_mad4joomla/includes/evolution.php');
		}
	else 
	{
	global $mainframe;
	define("M4J_ABS", $mainframe->getCfg('absolute_path'));
	}
/**
* @version $Id: mad4jobs.php 10041 2008-03-18 21:48:13Z fahrettinkutyol $
* @package joomla
* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
* @copyright (C) mad4media , www.mad4media.de
*/
function com_install()
{	
	



	global $mosConfig_lang;
	include_once (M4J_ABS.'/administrator/components/com_mad4joomla/defines.mad4joomla.php');
	
	
	if(file_exists(M4J_ABS.'/administrator/components/com_mad4joomla/language/'.$mosConfig_lang.'/info.php')) 
		include_once(M4J_ABS.'/administrator/components/com_mad4joomla/language/'.$mosConfig_lang.'/info.php');
	else include_once(M4J_ABS.'/administrator/components/com_mad4joomla/language/english/info.php');
	
}

?>
