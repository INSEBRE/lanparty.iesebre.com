<?php
/**
* DFContact - A Joomla! contact form component
* @version 1.5
* @package DFContact
* @copyright (C) 2007 by Daniel Filzhut
* @license Released under the terms of the GNU General Public License
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'admin_html' ) );

$option = JRequest::getVar( 'option', '' );
$dfcontact = JRequest::getVar( 'dfcontact', array(0), 'post', 'array' );

switch ($task) {

	case "save":
		save( $option, $dfcontact, true);
		break;

	case "apply":
		save( $option, $dfcontact);
		break;

	case "cancel":
		$mainframe->redirect( 'index.php' );
		break;

	default:
		show( $option );
		break;
}


############################################################################
############################################################################

function show( $option ) {

	// Define config file
	$configFile = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_dfcontact' . DS . 'config.dfcontact.php';

	// Load configuration data
	include( $configFile );

	// Display configuration tables
	HTML_dfcontact::show( $option, $dfcontact );

}

function save( $option, $dfcontactNew, $returnToMain = false ) {
	
	global $mainframe;

	// Define config file
	$configFile = JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_dfcontact' . DS . 'config.dfcontact.php';

	// Load configuration data
	include( $configFile );
	
	// Rescue standard layout data
	$addressFormatTemplates = $dfcontact['addressFormat']['templates'];
	$addressFormatTemplates['self'] = $dfcontactNew['addressFormat']['templates']['self'];
	$dfcontactNew['addressFormat']['templates'] = $addressFormatTemplates;

	// Make config file writeable
	@chmod ( $configFile, 0766 );

	// Create config file contents
	$config = "<?php\n";
	$config .= "\$dfcontact = " . var_export( $dfcontactNew, TRUE ) . ";\n";
	$config .= "?>";

	// Try to write config file
	jimport('joomla.filesystem.file');
	if (JFile::write($configFile, $config)) {
		$mainframe->redirect( 'index.php?option=' . ($returnToMain ? '' : $option), JText::_( 'CONFIG_SUCCESS') );
	} else {
		$mainframe->redirect( 'index.php?option=' . $option, JText::_( 'ERROR') . ': ' . JText::_( 'CONFIG_ERROR_FILE_WRITE') );
	}


}

?>