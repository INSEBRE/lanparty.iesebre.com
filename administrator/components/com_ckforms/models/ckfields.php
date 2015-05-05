<?php
/**
 * Ckfields Model for CK form Component
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Ckfields Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class CkformsModelCkfields extends JModel
{
	/**
	 * fields data array
	 *
	 * @var array
	 */
	var $_data;

	function __construct()
	{
		parent::__construct();

		$this->setId((int)JRequest::getVar('fid',  0));
		
	}
	
	/**
	 * Method to set the parent form identifier
	 *
	 * @access	public
	 * @param	int parent form identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
	}

	function getId()
	{
		return $this->_id;
	}
		
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$query = ' SELECT * from #__ckfields c where c.fid='.$this->_id.' '.
			'ORDER BY ordering';

		return $query;
	}

	/**
	 * Retrieves the fields data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
						
			$this->_data = $this->_getList( $query );
			
		}

		return $this->_data;
	}

	/**
	 * Retrieves all the fields data
	 * @return array Array of objects containing the data from the database
	 */
	function getAllData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = ' SELECT * from #__ckfields c ORDER BY ordering';
						
			$this->_data = $this->_getList( $query );
			
		}

		return $this->_data;
	}
}
