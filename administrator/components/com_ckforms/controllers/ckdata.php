<?php
/**
 * ckdata Controller for CK forms Component
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
 * ckdata Controller
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsControllerCkdata extends JController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
	
		parent::__construct();

	}

	/**
	 * Export data saved in database
	 * @return void
	 */
	function export() {
			
		$model = $this->getModel('ckdata');
		
		$items = $model->getData();
		$fields = $model->getDatafields();
		
		$document = &JFactory::getDocument();
		$doc = &JDocument::getInstance('text');
		$document = $doc;
		
		header("Expires: Sun, 1 Jan 2000 12:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	
		header( "Content-type: application/vnd.ms-excel; charset=UTF-16LE" ); 
		
		header("Content-disposition: attachment; filename=ckforms_" . date("Ymd").".csv");  

		$n=count( $fields );
		for ($i=0; $i < $n; $i++)
		{
			$rowField = $fields[$i];
			if ($rowField->typefield != 'button')
			{
				$unicode_str_for_Excel = mb_convert_encoding( $rowField->name, 'UTF-16LE', 'UTF-8'); 

				$data .= "\"".$unicode_str_for_Excel."\"";
				if ($i < $n-1) $data .= ";";				
			}			
		}

		echo $data." \n";
		
		$n=count( $items );
		for ($i=0; $i < $n; $i++)
		{
			$row = $items[$i];
			
			$data = '';
			$z=count( $fields );
			for ($j=0; $j < $z; $j++)
			{
				$rowField = $fields[$j];
				if ($rowField->typefield != 'button')
				{
					$prop=$rowField->name;
					
					$unicode_str_for_Excel = mb_convert_encoding( $row->$prop, 'UTF-16LE', 'UTF-8'); 
					
					$data .= "\"".$unicode_str_for_Excel."\"";		
					if ($j < $z-1) $data .= ";";			
				}
			}
			
			echo $data." \n";
		}

	}	

	/**
	 * remove record(s) from database
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('ckdata');
		$fid = JRequest::getVar( 'fid', -1 );
		
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More record could not be Deleted' );
		} else {
			$msg = JText::_( 'Field(s) Deleted' );
		}

		$this->setRedirect('index.php?option=com_ckforms&controller=ckdata&fid='.$fid, $msg );
	}
	
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{	
		JRequest::setVar( 'view', 'ckdata' );
		
		parent::display();
	}

	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function detail()
	{
		JRequest::setVar( 'view', 'ckdata' );
		JRequest::setVar( 'layout', 'detail' );
		
		parent::display();
	}
	
}
?>
