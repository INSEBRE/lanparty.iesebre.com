<?php
/**
* DFContact - A Joomla! contact form component
* @version 1.5
* @package DFContact
* @copyright (C) 2007 by Daniel Filzhut
* @license Released under the terms of the GNU General Public License
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* @package DFContact
*/
class HTML_dfcontact {

	function show( $option, $dfcontact) {
		
		//Load switcher behavior
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.switcher');
		
		// Build the component's submenu
		$contents = '';
		$tmplpath = dirname(__FILE__) . DS . 'tmpl';
		ob_start();
		require_once( $tmplpath . DS . 'navigation.php' );
		$contents = ob_get_contents();
		ob_end_clean();

		// Set document data
		$document =& JFactory::getDocument();
		$document->setBuffer($contents, 'modules', 'submenu');
		
		?>
		
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.getElementById('adminForm');
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			submitform( pressbutton );
		}
		</script>
		
		<form action="index.php?option=com_dfcontact" method="post" name="adminForm" id="adminForm">

		<div id="config-document">
		
			<div id="page-general">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'general.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-your_data">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'your_data.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-form_fields">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'form_fields.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-address_format">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'address_format.php'); ?>
					</td>
				</tr>
				</table>
			</div>

			<div id="page-about">
				<table class="noshow">
				<tr>
					<td>
					<?php require_once(JPATH_COMPONENT . DS . 'tmpl' . DS . 'about.php'); ?>
					</td>
				</tr>
				</table>
			</div>
			
		</div>

		<div class="clr"></div>
		
		<input type="hidden" name="task" value="" />
		
		</form>
		
		<?php
	}

}

?>