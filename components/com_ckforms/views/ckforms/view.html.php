<?php
/**
 * Hello View for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package		Joomla.Tutorials
 * @subpackage	Components
 */
class CkformsViewCkforms extends JView
{
	function display($tpl = null)
	{	
		global $mainframe;
		$ckforms = & $this->get('Data');
	
		$params =& $mainframe->getParams('com_content');
		$this->assignRef('params' , $params);
	
		$this->assignRef( 'ckforms',$ckforms );
		
		$formLink = "index.php?option=com_ckforms&view=ckforms&task=send&id=".$ckforms->id;		
		$this->assignRef( 'formLink',$formLink );

		$document =& JFactory::getDocument();
		$document->addScript(JURI::root(true).'/components/com_ckforms/js/jquery-1.3.2.min.js');
		$document->addScript(JURI::root(true).'/components/com_ckforms/js/jquery.tooltip.min.js');
		$document->addScript(JURI::root(true).'/components/com_ckforms/js/ui.datepicker.packed.js');
		$document->addScript(JURI::root(true).'/components/com_ckforms/js/jquery.validate.pack.js');

		$document->addStyleSheet(JURI::root(true).'/components/com_ckforms/css/ckforms.css');
		$document->addStyleSheet(JURI::root(true).'/components/com_ckforms/css/ui.datepicker.css');

		parent::display($tpl);
	}

}
?>
