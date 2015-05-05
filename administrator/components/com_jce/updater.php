<?php
/**
* @version		$Id: updater.php 116 2009-06-23 11:32:04Z happynoodleboy $
* @package		JCE Component
* @copyright	Copyright (C) 2006 - 2009 Ryan Demmer. All rights reserved.
* @license		GNU/GPL
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
class JCEUpdater extends JObject 
{
	/**
	* Constructor activating the default information of the class
	*
	* @access	protected
	*/
	function __construct()
	{
		$language =& JFactory::getLanguage();	
		$language->load('com_jce', JPATH_ADMINISTRATOR);
	}
	/**
	 * Returns a reference to a editor object
	 *
	 * This method must be invoked as:
	 * 		<pre>  $browser = &JContentEditor::getInstance();</pre>
	 *
	 * @access	public
	 * @return	JCE  The editor object.
	 * @since	1.5
	 */
	function &getInstance()
	{
		static $instance;

		if (!is_object($instance)) {
			$instance = new JCEUpdater();
		}
		return $instance;
	}
	
	function log($msg)
	{
		jimport('joomla.error.log');
		$log = &JLog::getInstance('update.txt');
		$log->addEntry(array('comment' => 'LOG: '.$msg));
	}
	
	/**
	 * Check upgrade / database status
	 */
	function initCheck()
	{	
		global $mainframe;
		// Check Plugins DB
		if (!$this->checkTableContents('plugins')) {
			$link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=plugins', JText::_('DB CREATE RESTORE'));			
			return $this->redirect(JText::_('DB PLUGINS ERROR') .' - '. $link, 'error');
		}
		// Check Groups DB
		if (!$this->checkTableContents('groups')) {
			$link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=groups', JText::_('DB CREATE RESTORE'));			
			return $this->redirect(JText::_('DB GROUPS ERROR') .' - '. $link, 'error');
		}
		// Check Editor is installed
		if (!$this->checkEditorFiles()) {
			return $this->redirect(JText::_('EDITOR FILES ERROR'), 'error');
		}
		if (!$this->checkEditor() && $this->checkEditorFiles()) {
			$link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=editor', JText::_('EDITOR INSTALL'));
			return $this->redirect(JText::_('EDITOR INSTALLED MANUAL ERROR') .' - '. $link, 'error');
		}
		// Check Editor is installed
		if (!$this->checkEditor()) {
			return $this->redirect(JText::_('EDITOR INSTALLED ERROR'), 'error');
		}
		// Check Editor is enabled
		if (!$this->checkEditorEnabled()) {
			return $this->redirect(JText::_('EDITOR ENABLED ERROR'), 'error');
		}

		// Check Update
		if (!$this->checkUpdate()) {
			$link = JHTML::link('index.php?option=com_jce&amp;task=repair&amp;type=update', JText::_('DB UPDATE'));			
			return $this->redirect(JText::_('DB UPDATE MSG') .' - '. $link, 'error');
		}
	}
	/**
	 * Redirect with message
	 * @param object $msg[optional] Message to display
	 * @param object $state[optional] Message type
	 */
	function redirect($msg = '', $state = '')
	{
		global $mainframe;
		if ($msg) {
			$mainframe->enqueueMessage($msg, $state);
		}
		JRequest::setVar('type', 'cpanel');
		JRequest::setVar('task', '');
		
		return false;	
	}

	/**
	 * Check whether a table exists
	 * @return boolean 
	 * @param string $table Table name
	 */
	function checkTable($table)
	{
		$db		=& JFactory::getDBO();	
		
		$tables = $db->getTableList();
		
		if (!empty($tables)) {
			// swap array values with keys, convert to lowercase and return array keys as values
			$tables = array_keys(array_change_key_case(array_flip($tables)));
			// convert prefix to lowercase
			$prefix = strtolower($db->replacePrefix('#__jce_'.$table));
			
			return in_array($prefix, $tables);
		}
		
		// try with query
		$query = 'SELECT COUNT(id) FROM #__jce_' . $table;
		$db->setQuery($query);
		
		return $db->query();
	}
	
	function checkTableContents($table)
	{
		$db	=& JFactory::getDBO();

		if ($this->checkTable($table)) {
			$query = 'SELECT COUNT(id) FROM #__jce_' . $table;
			$db->setQuery($query);
		
			return $db->loadResult();
		}
		
		return false;
	}

	/**
	 * Rename / Backup all tables
	 */
	function purgeDB()
	{
		global $mainframe;
		$db		=& JFactory::getDBO();	
		$tables = array('plugins', 'extensions', 'groups');
		
		foreach ($tables as $table) {
			// Backup table to temp. Will be removed on uninstall			
			if (!$this->backupTable($table)) {
				$msg 	= JText::_('DB PURGE '. strtoupper($table) .' ERROR');
				$state 	= 'error';
			} else {
				$msg 	= JText::_('DB PURGE '. strtoupper($table) .' SUCCESS');
				$state 	= '';
			}
			$mainframe->enqueueMessage($msg, $state);
		}
		$this->redirect();
	}
	/**
	 * Check if all backup tables exist
	 * @return boolean
	 */
	function checkTables()
	{
		$ret 	= false;
		$tables = array('plugins', 'groups');
		foreach ($tables as $table) {
			$ret = $this->checkTable($table);
		}
		return $ret;
	}
	/**
	 * Remove all backup tables
	 */
	function removeDB()
	{
		$db	=& JFactory::getDBO();	
		
		$tables = array('plugins', 'groups', 'extensions');
		
		foreach ($tables as $table) {
			$query = 'DROP TABLE IF EXISTS #__jce_'. $table;
			$db->setQuery($query);
			
			$db->query();
		}
	}
	/**
	 * Check for earlier version to trigger update
	 * @return boolean
	 */
	function checkUpdate()
	{
		// Check for Readmore plugin indicates 1.5.0
		global $mainframe;
		$db	=& JFactory::getDBO();
		
		$ret = false;

		$query = 'SELECT count(id)'
		. ' FROM #__jce_plugins'
		. ' WHERE name = '. $db->Quote('readmore')
		;	
		$db->setQuery($query);
		$ret = $db->loadResult() ? false : true;
		
		return $ret;
	}
	/**
	 * Check whether the editor is installed
	 * @return boolean
	 */
	function checkEditor()
	{
		$db	=& JFactory::getDBO();
		
		$query = 'SELECT id'
		. ' FROM #__plugins'
		. ' WHERE element = '. $db->Quote('jce')
		;
		$db->setQuery($query);		
		return $db->loadResult();
	}
	/**
	 * Check for existence of editor files and folder
	 * @return boolean
	 */
	function checkEditorFiles()
	{
		$path = JPATH_PLUGINS .DS. 'editors';
		// Check for JCE plugin files
		return file_exists($path .DS. 'jce.php') && file_exists($path .DS. 'jce.xml') && is_dir($path .DS. 'jce');
	}
	/**
	 * Check if the editor is enabled
	 * @return boolean
	 */
	function checkEditorEnabled()
	{
		$db	=& JFactory::getDBO();
		
		$query = 'SELECT published FROM #__plugins'
		.' WHERE element = '. $db->Quote('jce')
		;
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 * Update the JCE Tables
	 * @return Redirect
	 * @param object $install[optional]
	 */
	function updateDB($install = false)
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		
		if (!$this->update()) {
			// Unable to perform update!
			$mainframe->enqueueMessage(JText::_('UPDATE ERROR'), 'error');
		}
		
		// Add Admin Menu options
		$query = "UPDATE #__components SET `admin_menu_img` = '../administrator/components/com_jce/img/logo.png'"
		. " WHERE link = " . $db->Quote('option=com_jce')
		;
		
		$db->setQuery($query);
		if (!$db->query()) {
			$mainframe->enqueueMessage(JText::_('ADMIN MENU IMAGE ERROR'), 'error');
		}
		if (!$install) {	
			$this->redirect();
		}
	}

	/**
	 * Create the Plugins table
	 * @return boolean
	 */
	function createPluginsTable()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		
		$query = "CREATE TABLE IF NOT EXISTS `#__jce_plugins` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(255) NOT NULL,
		`name` varchar(255) NOT NULL,
		`type` varchar(255) NOT NULL,
		`icon` varchar(255) NOT NULL,
		`layout` varchar(255) NOT NULL,
		`row` int(11) NOT NULL,
		`ordering` int(11) NOT NULL,
		`published` tinyint(3) NOT NULL,
	 	`editable` tinyint(3) NOT NULL,
		`iscore` tinyint(3) NOT NULL,
		`checked_out` int(11) NOT NULL,
		`checked_out_time` datetime NOT NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `plugin` (`name`)
		);";
		$db->setQuery($query);
		
		if (!$db->query()) {
			$mainframe->enqueueMessage(JText::_('CREATE TABLE PLUGINS ERROR').' : '.$db->stdErr(), 'error');
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Create the Groups table
	 * @return boolean
	 */
	function createGroupsTable()
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		
		$query = "CREATE TABLE IF NOT EXISTS `#__jce_groups` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`description` varchar(255) NOT NULL,
		`users` text NOT NULL,
		`types` varchar(255) NOT NULL,
		`components` text NOT NULL,
		`rows` text NOT NULL,
		`plugins` varchar(255) NOT NULL,
		`published` tinyint(3) NOT NULL,
		`ordering` int(11) NOT NULL,
		`checked_out` tinyint(3) NOT NULL,
		`checked_out_time` datetime NOT NULL,
		`params` text NOT NULL,
		PRIMARY KEY (`id`)
		);";
		$db->setQuery($query);
		
		if (!$db->query()) {
			$mainframe->enqueueMessage(JText::_('CREATE TABLE GROUPS ERROR').' : '.$db->stdErr(), 'error');
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Update Groups table
	 * @return boolean
	 */
	function update()
	{
		$mainframe =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		
		// get non-core plugins data
		$query = 'SELECT * FROM #__jce_plugins';
		$db->setQuery($query);
		$plugins = $db->loadObjectList();
		
		// get groups data
		$query = 'SELECT * FROM #__jce_groups';
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		// remove plugins table
		$query = 'DROP TABLE IF EXISTS #__jce_plugins';
		$db->setQuery($query);
		$db->query();
		
		// remove groups table
		$query = 'DROP TABLE IF EXISTS #__jce_groups';
		$db->setQuery($query);
		$db->query();
		
		// remove extensions table
		$query = 'DROP TABLE IF EXISTS #__jce_extensions';
		$db->setQuery($query);
		$db->query();
		
		// array of plugin ids
		$ids = array();
		
		// create plugins table and install data
		if (!$this->installPlugins(true)) {
			return false;
		}
		
		// create empty groups tables
		if (!$this->createGroupsTable()) {
			return false;
		}	
		
		JTable::addIncludePath(dirname(__FILE__) .DS. 'plugins');
		JTable::addIncludePath(dirname(__FILE__) .DS. 'groups');
		
		// install plugins	
		if (!empty($plugins)) {
			foreach ($plugins as $plugin) {
				// get ids of new plugins
				$query = 'SELECT id FROM #__jce_plugins WHERE name = ' . $db->Quote($plugin->name);			
				$db->setQuery($query);
				$ids[$plugin->id] = $db->loadResult();
				
				if ($plugin->iscore == 0) {
					// store non-core plugins
					$row =& JTable::getInstance('plugin', 'JCETable');						
					// Pass properties to $row object
					$row->title 	= $plugin->title;
					$row->name		= $plugin->name;
					$row->icon		= $plugin->icon;
					$row->layout	= $plugin->layout;
					$row->row		= $plugin->row;
					$row->ordering	= $plugin->ordering;
					// Store
					if (!$row->store()) {
						$mainframe->enqueueMessage(JText::_('PLUGIN INSTALL ERROR '. $plugin->title), 'error');
					}
					// store new plugin id
					$ids[$plugin->id] = $row->id;
				}
			}
		}
		
		// install groups data
		foreach ($groups as $group) {
			$table =& JTable::getInstance('groups', 'JCETable');
			
			$newplugins = array();
			
			// get old plugins
			$oldplugins = explode(',', $group->plugins);
			
			if (!empty($ids)) {
				// map old ids to new ids
				foreach ($oldplugins as $oldplugin) {
					if (isset($ids[$oldplugin])) {
						$newplugins[] = $ids[$oldplugin];
					}
				}
			} else {
				$newplugins = $oldplugins;
			}

			// store plugins
			$table->plugins = implode(',', $newplugins);
			
			$newrows = array();
			
			// get old rows
			$oldrows = explode(';', $group->rows);
			
			foreach ($oldrows as $oldrow) {
				$buttons = explode(',', $oldrow);
				$newrow = array();
				
				foreach ($buttons as $button) {
					if (isset($ids[$button])) {
						$newrow[] = $ids[$button];
					}
				}
				/// add buttons to row
				$newrows[] = implode(',', $newrow);
			}
			
			// store rows
			$table->rows = implode(';', $newrows);
			
			// Add additional properties
			$table->name 		= $group->name;
			$table->description = $group->description;
			$table->components	= $group->components;
			$table->users		= $group->users;
			$table->types		= $group->types;
			$table->published	= $group->published;
			$table->ordering	= $group->ordering;
			$table->params		= $group->params;
			
			if (!$table->store()) {
				$mainframe->enqueueMessage(JText::_('GROUP UPDATE ERROR ') . $group->name, 'error');
			}
		}	
		
		return true;
	}
	
	/**
     * Install Groups
     * @return boolean
     * @param object $install[optional]
     */
    function installGroups($install = false)
    {
        $mainframe =& JFactory::getApplication();        
        $db =& JFactory::getDBO();
        
        $ret = false;
        
        JTable::addIncludePath(dirname(__FILE__) . DS . 'groups');
        
        if ($this->createGroupsTable()) {
            $ret = true;
            
            $query = 'SELECT count(id) FROM #__jce_groups';
            $db->setQuery($query);
            
            $groups = array(
                'Default' 	=> false,
                'Front End' => false
            );
            
            // No Profiles table data
            if (!$db->loadResult()) {
                $manifest = dirname(__FILE__) . DS . 'groups' . DS . 'groups.xml';
                
                if (is_file($manifest)) {
                	$xml =& JFactory::getXMLParser('Simple');
			        
			        if ($xml->loadFile($manifest)) {   
			            $groups = $xml->document->getElementByPath('groups');
			            
			            foreach ($groups->children() as $group) {
			                $row =& JTable::getInstance('groups', 'JCETable');
			                
			                foreach ($group->children() as $item) {
			                    $key = $item->name();
			                    
			                    switch ($key) {
			                    	case 'plugins' :
			                    		$query = 'SELECT id FROM #__jce_plugins'
			                    		. ' WHERE name IN (' . preg_replace('#([a-z0-9_-]+)#', '"$1"', $item->data()) . ')'
			                    		. ' AND type = ' .$db->Quote('plugin');
			                    		$db->setQuery($query);
			                    		
			                    		$data = implode(',', $db->loadResultArray());
			                    		
			                    		break;
			                    	case 'rows' :
			                    		$ids = array();
			                    		$names = explode(';', $item->data());
			                    		foreach($names as $name) {
			                    			$query = 'SELECT id FROM #__jce_plugins'
			                    			. ' WHERE name IN (' . preg_replace('#([a-z0-9_-]+)#', '"$1"', $name) . ')';
			                    			$db->setQuery($query);
			                    		
			                    			$ids[] = implode(',', $db->loadResultArray());
			                    		}
										$data = implode(';', $ids);
			                    		
			                    		break;
			                    	default :
			                    		$data = $item->data();
			                    		break;
			                    }
			                    
			                    $row->$key = $data;
			                }
			                
			                if (!$row->store()) {
			                    $mainframe->enqueueMessage(JText::_('GROUP INSTALL ERROR'), 'error');
			                    return false;
			                }
			            }
					} else {
                    	$mainframe->enqueueMessage(JText::_('GROUP INSTALL ERROR'), 'error');
                	}
                }
            }
        }
        if (!$install) {
            //$this->redirect();
            $mainframe->redirect('index.php?option=com_jce');
        }
        return $ret;
    }
	
	/**
	 * Install Plugins
	 * @return boolean
	 * @param object $install[optional]
	 */
	function installPlugins($install = false)
	{
		$mainframe =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		
		$ret = false;
		
		JTable::addIncludePath(dirname(__FILE__) . DS . 'plugins');
        
        if ($this->createPluginsTable()) {
            $query = 'SELECT count(id) FROM #__jce_plugins';
            $db->setQuery($query);

            // No Plugins table data
            if (!$db->loadResult()) {
                $manifest = dirname(__FILE__) . DS . 'plugins' . DS . 'plugins.xml';
                
                if (is_file($manifest)) {
                	$xml =& JFactory::getXMLParser('Simple');
			        
			        if ($xml->loadFile($manifest)) {   
			            $plugins = $xml->document->getElementByPath('plugins');
			            
			            foreach ($plugins->children() as $plugin) {
			                $row =& JTable::getInstance('plugin', 'JCETable');
			                
			                foreach ($plugin->children() as $item) {
			                    $key = $item->name();
			                    if ($key) {
			                   		$row->$key = $item->data();
			                    }
			                }
			                
			                $row->iscore = 1;
			                
			                if (!$row->store()) {
			                    $mainframe->enqueueMessage(JText::_('PLUGIN INSTALL ERROR'), 'error');
			                }
			                
			                $row = null;
			            }
			            
			             $ret = true;
			            
					} else {
                    	$mainframe->enqueueMessage(JText::_('PLUGINS INSTALL ERROR'), 'error');
                    	return false;
                	}
                } else {
                    $mainframe->enqueueMessage(JText::_('PLUGINS INSTALL ERROR'), 'error');
                    return false;
                }
            }
        }

		if (!$install) {	
			$this->redirect();
		}
		return $ret;
	}
	
	/**
	 * Install the Editor Plugin
	 * @return boolean
	 * @param object $install[optional]
	 */
	function installEditor($install = false)
	{
		$mainframe =& JFactory::getApplication();
		$db =& JFactory::getDBO();
		$path 	= JPATH_PLUGINS .DS. 'editors';
		$ret 	= true;
		if ($this->checkEditorFiles()) {	
			// Sourced from various Joomla! core files including the installer plugin adapter			
			$xml =& JFactory::getXMLParser('Simple');
			$name = 'JCE Editor 1.5.x';
					
			if ($xml->loadFile($path .DS. 'jce.xml')) {
				$root =& $xml->document;	
				// Get the element of the tag names
				$name = $root->getElementByPath('name');
				$name = JFilterInput::clean($name->data(), 'string');
			}
			JTable::addIncludePath(JPATH_LIBRARIES .DS. 'joomla' .DS. 'database' .DS. 'table');
			// Get Editor id if installed
			$id = $this->checkEditor();
			$row =& JTable::getInstance('plugin');
			// Load editor if valid id
			if($id){
				$row->load($id);
			}
			$row->name 		= $name;
			$row->ordering 	= 0;
			$row->folder 	= 'editors';
			$row->iscore 	= 0;
			$row->access 	= 0;
			$row->published = 1;
			$row->client_id = 0;
			$row->element 	= 'jce';
			if (!$row->store()) {
				$mainframe->enqueueMessage(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
				$ret = false;
			}
		} else {
			$mainframe->enqueueMessage(JText::_('EDITOR FILES MISSING'), 'error');
			$ret = false;
		}
		$mainframe->enqueueMessage(JText::_('EDITOR INSTALL SUCCESS'));	
		$ret = true;
		if(!$install){
			$this->redirect();
		}else{
			return $ret;
		}
	}
	/**
	 * Uninstall the editor
	 * @return boolean
	 */	
    function removeEditor()
    {
        $mainframe =& JFactory::getApplication();
        $db = & JFactory::getDBO();
    
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
    
        $query = 'DELETE FROM #__plugins'
        .' WHERE folder = '.$db->Quote('editors')
        .' AND element = '.$db->Quote('jce')
        ;
    
        $db->setQuery($query);
        if (!$db->query()) {
            $msg = JText::sprintf('UNINSTALLEXT', 'Editor', JText::_('Error'));
            $ret = false;
        } else {
            $path = JPATH_PLUGINS.DS.'editors';
    
            $files = array (
            	$path.DS.'jce.php',
           	 	$path.DS.'jce.xml',
           	 	JPATH_SITE.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_editors_jce.ini'
            );
    
            foreach ($files as $file) {
                if (file_exists($file)) {
                    JFile::delete($file);
                }
            }
            JFolder::delete($path.DS.'jce');
			$msg = JText::sprintf('UNINSTALLEXT', 'Editor', JText::_('Success'));
			$ret = true;
        }
		$mainframe->enqueueMessage($msg);
		return $ret;
    }
}
?>