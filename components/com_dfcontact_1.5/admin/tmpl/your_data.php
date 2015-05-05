<fieldset class="adminform">
	<legend><?php echo JText::_( 'TAB_YOUR_DATA' ); ?></legend>
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
			if ( $key == "checkbox" || $key == "message" ) {
				continue;
			}
		?>
			<tr>
				<td class="key"><?php echo JText::_( strtoupper( $key ) ) . ":" ?></td>
				<td><input type="text" name="dfcontact[field][<?php echo $key; ?>][value]" value="<?php echo ( !empty( $value["value"] ) ? htmlspecialchars( $value["value"] ) : "" ); ?>"></td>
				<?php
				echo ($counter++ == 0 ? "<td align=\"left\" valign=\"top\" rowspan=\"" . sizeof($dfcontact["field"]) . "\">" . JText::_( 'TAB_YOUR_DATA_INFO') . "</td>" : "");
				?>
				<td></td>
			</tr>
		<?php
		}
		?>
		</tbody>
		</table>
</fielset>