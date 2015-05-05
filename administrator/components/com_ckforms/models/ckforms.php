<?php
/**
 * Ckforms for CK Form Component
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
 * Ckforms Model
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class CkformsModelCkforms extends JModel
{
	/**
	 * forms data array
	 *
	 * @var array
	 */
	var $_data;


	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
		$query = ' SELECT a.*,b.username,(select count(*) from #__ckfields c where c.fid=a.id) nbfields FROM #__ckforms a, #__users b '.
			'where a.created_by = b.id';
		;

		return $query;
	}

	/**
	 * Retrieves the hello data
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

}
