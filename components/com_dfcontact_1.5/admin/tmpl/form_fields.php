<fieldset class="adminform">
	<legend><?php echo JText::_( 'TAB_FORM_FIELDS' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<?php
		$counter = 0;
		$dfcontact["field"] = ( !empty( $dfcontact["field"] ) && is_array( $dfcontact["field"] ) ? $dfcontact["field"] : array() );
		reset( $dfcontact["field"] );
		while( list( $key, $value ) = each( $dfcontact["field"] ) ) {
			if ( $key == "checkbox" ) {
				continue;
			}
			?>
			<tr>
				<td class="key"><?php echo JText::_( $key ) . ":"; ?></td>
				<td><?php
				$options = array();
				$options[] = JHTMLSelect::option( 0, JText::_( 'HIDE') );
				$options[] = JHTMLSelect::option( 1, JText::_( 'SHOW') );
				$options = JHTMLSelect::genericList( $options, 'dfcontact[field][' . $key . '][display]', 'class="inputbox" size="1" onChange="dfcontactRefreshDuty(\'dfcontactfield' . $key . '\')"', 'value', 'text', ( !empty( $value["display"] ) ? htmlspecialchars( $value["display"] ) : "" ) );
				echo $options;
				echo "&nbsp;&nbsp;&nbsp;";
				$options = array();
				$options[] = JHTMLSelect::option( 0, JText::_( 'OPTIONAL') );
				$options[] = JHTMLSelect::option( 1, JText::_( 'DUTY') );
				$options = JHTMLSelect::genericList( $options, 'dfcontact[field][' . $key . '][duty]', 'class="inputbox" size="1"' . ( !$value["display"] ? " disabled=\"disabled\"" : "" ), 'value', 'text', ( !empty( $value["duty"] ) ? htmlspecialchars( $value["duty"] ) : "" ) );
				echo $options;
				?></td>
				<?php
				echo ($counter++ == 0 ? "<td align=\"left\" valign=\"top\" rowspan=\"" . ( sizeof( $dfcontact["field"] ) - 1 ) . "\">" . JText::_( 'TAB_FORM_FIELDS_INFO') . "</td>" : "");
				?>
				<td></td>
			</tr>
		<?php
		}
		?>
			<tr>
				<td class="key"><?php echo JText::_( 'CHECKBOX') . ":" ?></td>
				<td><?php				
				$options = array();
				$options[] = JHTMLSelect::option( 0, JText::_( 'HIDE') );
				$options[] = JHTMLSelect::option( 1, JText::_( 'SHOW') );
				$options = JHTMLSelect::genericList( $options, 'dfcontact[field][checkbox][display]', 'class="inputbox" size="1" onChange="dfcontactRefreshDuty(\'dfcontactfieldcheckbox\')"', 'value', 'text', ( !empty( $dfcontact["field"]["checkbox"]["display"] ) ? htmlspecialchars( $dfcontact["field"]["checkbox"]["display"] ) : "" ) );
				echo $options;
				echo "&nbsp;&nbsp;&nbsp;";
				$options = array();
				$options[] = JHTMLSelect::option( 0, JText::_( 'OPTIONAL') );
				$options[] = JHTMLSelect::option( 1, JText::_( 'DUTY') );
				$options = JHTMLSelect::genericList( $options, 'dfcontact[field][checkbox][duty]', 'class="inputbox" size="1"' . ( empty( $dfcontact["field"]["checkbox"]["display"] ) ? " disabled=\"disabled\"" : "" ), 'value', 'text', ( !empty( $dfcontact["field"]["checkbox"]["duty"] ) ? htmlspecialchars( $dfcontact["field"]["checkbox"]["duty"] ) : "" ) );
				echo $options;
				?></td>
				<td><?php echo JText::_( 'CHECKBOX_INFO') ?></td>
				<td></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_( 'CHECKBOX_TEXT') . ":" ?></td>
				<td><input type="text" name="dfcontact[field][checkbox][text]" value="<?php echo ( !empty( $dfcontact["field"]["checkbox"]["text"] ) ? htmlspecialchars( $dfcontact["field"]["checkbox"]["text"] ) : "" ); ?>"></td>
				<td><?php echo JText::_( 'CHECKBOX_TEXT_INFO') ?></td>
				<td></td>
			</tr>
		</tbody>
		</table>
		<script language="Javascript">
		function dfcontactRefreshDuty(field) {
			var fieldnameDisplay = field + "display";
			var fieldnameDuty = field + "duty";
			if ( document.getElementById(fieldnameDisplay).options[document.getElementById(fieldnameDisplay).selectedIndex].value == "0" ) {
				document.getElementById(fieldnameDuty).disabled = true;
			} else {
				document.getElementById(fieldnameDuty).disabled = false;
			}
		}
		</script>
</fielset>