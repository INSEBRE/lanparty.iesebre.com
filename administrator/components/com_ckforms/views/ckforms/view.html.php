<?php
/**
 * ckforms View for CK forms Component
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * ckforms View
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsViewCkforms extends JView
{
	/**
	 * ckforms view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$doc =& JFactory::getDocument();
		$css = '.icon-48-ckform {background:url(../administrator/components/com_ckforms/images/logo-banner.png) no-repeat;}';
   		$doc->addStyleDeclaration($css);
	
		JToolBarHelper::title(JText::_( 'CK Forms' ), 'ckform' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::custom( 'edit_css', 'css.png', 'css_f2.png', JText::_('Edit CSS'), false, false );
		JToolBarHelper::custom( 'backup', 'save.png', 'save_f2.png', JText::_('Backup'), false, false );
		JToolBarHelper::custom( 'restore', 'save.png', 'save_f2.png', JText::_('Restore'), false, false );

		// Get data from the model
		$items = & $this->get( 'Data');

		$this->assignRef('items', $items);

		parent::display($tpl);
	}
}
