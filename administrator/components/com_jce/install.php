<?php
/**
 * @version		$Id: install.php 115 2009-06-23 11:31:41Z happynoodleboy $
 * @package		JCE Admin Component
 * @copyright	Copyright (C) 2006 - 2009 Ryan Demmmer. All rights reserved.
 * @license		GNU/GPL
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
defined('_JEXEC') or die ('Restricted access');
/**
 * Installer function
 * @return
 */
function com_install()
{
    global $mainframe;
    $db = & JFactory::getDBO();

    jimport('joomla.filesystem.folder');
    jimport('joomla.filesystem.file');

    $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jce';

    // Remove legacy file
    if (file_exists($path.DS.'admin.jce.php')) {
        @JFile::delete($path.DS.'admin.jce.php');
    }
    // Load updater class
    require_once ($path.DS.'updater.php');

    $updater = & JCEUpdater::getInstance();
    // Install Plugins data
    if ($updater->installPlugins(true)) {
    	 // Install Groups data
    	$updater->installGroups(true);
    }

    jimport('joomla.installer.installer');
    $installer = & JInstaller::getInstance();

    $source 	= $installer->getPath('source');
    $packages 	= $source.DS.'packages';
    // Get editor and plugin packages
	if (is_dir($packages)) {
		$editor = JFolder::files($packages, 'plg_jce_15\d+?(_patch)?\.zip', false, true);
	}

    $language = & JFactory::getLanguage();
    $language->load('com_jce', JPATH_ADMINISTRATOR);
    
    $component 	= JComponentHelper::getComponent('com_jce');
	$params 	= explode("\n", $component->params);
    
     // get the component installer
	$componentInstaller 	= & JInstaller::getInstance();				
	$componentXML 			= JApplicationHelper::parseXMLInstallFile($componentInstaller->getPath('manifest'));

    $img_path	 = JURI::root().'/administrator/components/com_jce/img/';
    $out 	 	 = '<table class="adminlist" style="width:50%;">';
    $out 		.= '<tr><th class="title" style="width:65%">'.JText::_('Extension').'</th><th class="title" style="width:30%">'.JText::_('Version').'</th><th class="title" style="width:5%">&nbsp;</th></tr>';
    $out 		.= '<tr><td>'.JText::_('JCE ADMIN TITLE').'</td><td>' . $componentXML['version'] . '</td><td class="title" style="text-align:center;">'.JHTML::image($img_path.'tick.png', JText::_('Success')).'</td></tr>';
	$out 		.= '<tr><td colspan="3">'.JText::_($installer->message).'</td></tr>';

    $editor_img 	= 'delete.png';
    $editor_result 	= JText::_('Error');

    if (! empty($editor)) {
        if (is_file($editor[0])) {
            if ($data = JInstallerHelper::unpack($editor[0])) {
				// Add JTable include path
                JTable::addIncludePath(JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table');
            	
            	$editorInstaller = new JInstaller();
            	// install editor
            	if ($editorInstaller->install($data['extractdir'])) {
            		$editorXML = JApplicationHelper::parseXMLInstallFile($editorInstaller->getPath('manifest'));
            		
            		// update plugin name
            		$query = 'UPDATE #__plugins'
					. ' SET name = '.$db->Quote($editorXML['name'])
					. ' WHERE folder = '.$db->Quote('editors')
					. ' AND element = '.$db->Quote('jce')
					;
					$db->setQuery($query);
					$db->query();
					
					$editor_img = 'tick.png';
                    $editor_result = JText::_('Success');
                    $params[] = 'package=1';
					
            	} else {
                    $editor_img = 'delete.png';
                    $editor_result = JText::_('Error');
                    $params[] = 'package=0';
                }
                // cleanup
                JInstallerHelper::cleanupInstall($data['packagefile'], $data['extractdir']);
            }
			$out .= '<tr><td>'.JText::_('JCE EDITOR TITLE').'</td><td>' . $editorXML['version'] . '</td><td class="title" style="text-align:center;">'.JHTML::image($img_path.$editor_img, $editor_result).'</td></tr>';
			$out .= '<tr><td colspan="3">' . JText::_($editorInstaller->message) . '</td></tr>';
        }
    }
    
    $out .= '</table>';

	$installer->set('message', JText::_('JCE INSTALL SUMMARY'));
	$installer->set('extension.message', $out);

	// Add JTable include path
   	JTable::addIncludePath(JPATH_LIBRARIES.DS.'joomla'.DS.'database'.DS.'table');
	// store component params
	$row = & JTable::getInstance('component');
	$row->loadByOption('com_jce');
	$row->params = implode("\n", $params);
	$row->store();
    
	if (is_dir($packages)) {
    	// Delete packages folder
    	@JFolder::delete($packages);
	}
}
/**
 * Uninstall function
 * @return
 */
function com_uninstall()
{
    require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jce'.DS.'updater.php');

    $updater = & JCEUpdater::getInstance();
    $updater->removeDB();

    $params = & JComponentHelper::getParams('com_jce');
    
    if ($params->get('package')) {
        $updater->removeEditor();
    }
}
?>
