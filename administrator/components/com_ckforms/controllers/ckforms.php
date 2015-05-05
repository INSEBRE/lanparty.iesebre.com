<?php
/**
 * ckforms Controller for CK Forms Component
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * ckforms Controller
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsControllerCkforms extends CkformsController
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
	 * display the CK Forms CSS
	 * @return void
	 */
	function edit_css() 
	{
		$this->setRedirect("index.php?option=com_ckforms&controller=cktools&task=editCSS", $msg);
	}

	/**
	 * display the CK Forms Backup page
	 * @return void
	 */
	function backup() 
	{
		$this->setRedirect("index.php?option=com_ckforms&controller=cktools&task=backup", $msg);
	}

	/**
	 * display the CK Forms Restore page
	 * @return void
	 */
	function restore() 
	{
		$this->setRedirect("index.php?option=com_ckforms&controller=cktools&task=restore", $msg);
	}
	
	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'ckform' );
		JRequest::setVar( 'layout', 'form'  );
		
		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('ckform');
		
		if ($model->store($post)) {
			$msg = JText::_( 'Form Saved' )." !";
		} else {
			$msg = JText::_( 'Error Saving Form' );
		}

		$task = JRequest::getCmd( 'task' );
		
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_ckforms&controller=ckforms&task=edit&cid[]='. $model->getId() ;
				break;

			case 'save':
			default:
				$link = 'index.php?option=com_ckforms';
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
		$model = $this->getModel('ckform');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Forms Could not be Deleted' );
		} else {
			$msg = JText::_( 'Form(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_ckforms', $msg );
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_ckforms', $msg );
	}
	
	/**
	 * publish record
	 * @return void
	 */
	function publish()
	{
		$this->setRedirect( 'index.php?option=com_ckforms' );
		
		// Initialize variables
		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__ckforms'
		. ' SET published = ' . (int) $publish
		. ' WHERE id IN ( '. $cids.'  )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			return JError::raiseWarning( 500, $row->getError() );
		}
		$this->setMessage( JText::sprintf( $publish ? 'Forms published' : 'Forms unpublished', $n ) );
	}	
	
	/**
	 * display the fields list for the selected form
	 * @return void
	 */
	function fields()
	{
		$fid = JRequest::getVar( 'id', -1);
		$this->setRedirect( "index.php?option=com_ckforms&controller=ckfields&fid=".$fid, $msg );
	}	
}
?>
