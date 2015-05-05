<?php

/**
 * ckfields Controller for CK Forms Component
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.controller' );

/**
 * ckfields Controller
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsControllerCkfields extends JController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
	
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply',		'save' );
		$this->registerTask( 'unpublish',	'publish' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit() {
	
		JRequest::setVar( 'view', 'ckfield' );
		JRequest::setVar( 'layout', 'ckfield_tpl');
		
		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		
		$model = $this->getModel('ckfield');
		$fid = JRequest::getVar( 'fid', -1 );
		
		if ($model->store($post)) {
			$msg = JText::_( 'Field Saved' )." !";
		} else {
			$msg = JText::_( 'Error Saving Field' );
		}

		$task = JRequest::getCmd( 'task' );
		
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_ckforms&controller=ckfields&task=edit&cid[]='.$model->getId().'&fid='.$fid;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_ckforms&controller=ckfields&fid='.$fid;
				break;
		}
		
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('ckfield');
		$fid = JRequest::getVar( 'fid', -1 );
		
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Field Could not be Deleted' );
		} else {
			$msg = JText::_( 'Field(s) Deleted' );
		}

		$this->setRedirect('index.php?option=com_ckforms&controller=ckfields&fid='.$fid, $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$fid = JRequest::getVar( 'fid', -1 );
		
		$this->setRedirect( 'index.php?option=com_ckforms&controller=ckfields&fid='. $fid, $msg );
	}
	
	function publish()
	{
		
		// Initialize variables
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );
		$fid		= JRequest::getVar( 'fid', -1 );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__ckfields'
		. ' SET published = ' . (int) $publish
		. ' WHERE id IN ( '. $cids.'  )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Fields published' : 'Fields unpublished', $n ) );
		
		$link = 'index.php?option=com_ckforms&controller=ckfields&fid='. $fid ;
		
		$this->setRedirect($link);
	}	
	
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{	
		JRequest::setVar( 'view', 'ckfields' );
		
		parent::display();
	}
	
	/**
	 * Method to order up the record
	 *
	 * @access	public
	 */
	function orderup()
	{
		$fid = JRequest::getVar( 'fid', -1 );

		$model = $this->getModel('ckfield');
		$model->move(-1);

		$this->setRedirect('index.php?option=com_ckforms&controller=ckfields&fid='. $fid);
	}

	/**
	 * Method to order down the record
	 *
	 * @access	public
	 */
	function orderdown()
	{
		$fid		= JRequest::getVar( 'fid', -1 );

		$model = $this->getModel('ckfield');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_ckforms&controller=ckfields&fid='. $fid);
	}

	/**
	 * Method to save the order
	 *
	 * @access	public
	 */
	function saveorder()
	{
		$fid = JRequest::getVar( 'fid', -1 );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order = JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('ckfield');
		$model->saveorder($cid, $order);

		$msg = JText::_( 'New ordering saved' );
		$this->setRedirect('index.php?option=com_ckforms&controller=ckfields&fid='. $fid, $msg );
	}	
}
?>
