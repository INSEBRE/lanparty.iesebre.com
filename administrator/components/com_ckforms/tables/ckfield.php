<?php
/**
 * ckfield table class
 * 
 * @package    CK.Joomla
 * @subpackage Components
 * @link http://www.cookex.eu
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


/**
 * ckfield Table class
 *
 * @package    CK.Joomla
 * @subpackage Components
 */
class TableCkfield extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	
	/**
	 * @var int
	 */
	var $fid = null;
	
	/**
	 * @var string
	 */
	var $label = null;

	/**
	 * @var string
	 */
	var $name = null;

	/**
	 * @var string
	 */
	var $typefield = null;

	/**
	 * @var int
	 */
	var $ordering = null;

	/**
	 * @var int
	 */
	var $published = 1;

	/**
	 * @var int
	 */
	var $mandatory = 0;

	/**
	 * @var string
	 */
	var $custominfo = null;

	/**
	 * @var string
	 */
	var $customerror = null;

	/**
	 * @var string
	 */
	var $customvalidation = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableCkfield(& $db) {			
		parent::__construct('#__ckfields', 'id', $db);
	}
	
	function store() {

		return parent::store();
	}
}
?>
