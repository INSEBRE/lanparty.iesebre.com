<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'EMAIL' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td class="key"><?php echo JText::_( 'DESTINATIONMAIL') . ":" ?></td>
			<td><input type="text" name="dfcontact[destinationMail]" value="<?php echo ( !empty( $dfcontact["destinationMail"] ) ? htmlspecialchars( $dfcontact["destinationMail"] ) : "" ); ?>"></td>
			<td><?php echo JText::_( 'DESTINATIONMAIL_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'HTMLMAILS') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[htmlMails]', 'class="inputbox"', ( !empty( $dfcontact["htmlMails"] ) ? htmlspecialchars( $dfcontact["htmlMails"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'HTMLMAILS_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'ADDSERVERDATA') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[addServerData]', 'class="inputbox"', ( !empty( $dfcontact["addServerData"] ) ? htmlspecialchars( $dfcontact["addServerData"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'ADDSERVERDATA_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'DISPLAYUSERINPUT') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[displayUserInput]', 'class="inputbox"', ( !empty( $dfcontact["displayUserInput"] ) ? htmlspecialchars( $dfcontact["displayUserInput"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'DISPLAYUSERINPUT_INFO') ?></td>
		</tr>
		</tbody>
		</table>
</fielset>

</td>
</tr>
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'SECURITY' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td class="key"><?php echo JText::_( 'CLICKABLELINKS') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[links]', 'class="inputbox"', ( !empty( $dfcontact["links"] ) ? htmlspecialchars( $dfcontact["links"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'CLICKABLELINKS_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'ONLINE_STATUS') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[onlineStatus]', 'class="inputbox"', ( !empty( $dfcontact["onlineStatus"] ) ? htmlspecialchars( $dfcontact["onlineStatus"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'ONLINE_STATUS_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'CAPTCHA') . ":" ?></td>
			<td><?php
			echo JHTML::_('select.booleanlist', 'dfcontact[captcha]', 'class="inputbox"', ( !empty( $dfcontact["captcha"] ) ? htmlspecialchars( $dfcontact["captcha"] ) : "0" ) );
			?></td>
			<td><?php echo JText::_( 'CAPTCHA_INFO') ?></td>
		</tr>
		</tbody>
		</table>
</fielset>

</td>
</tr>
<tr>
<td>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'LAYOUT' ); ?></legend>
		<table class="admintable">
		<colgroup>
			<col style="width:12em;" />
			<col style="width:23em;" />
			<col style="" />
		</colgroup>
		<tbody>
		<tr>
			<td class="key"><?php echo JText::_( 'PAGETITLE') . ":" ?></td>
			<td><input type="text" name="dfcontact[pageTitle]" value="<?php echo ( !empty( $dfcontact["pageTitle"] ) ? htmlspecialchars( $dfcontact["pageTitle"] ) : "" ); ?>"></td>
			<td><?php echo JText::_( 'PAGETITLE_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'INFOTEXT') . ":" ?></td>
			<td><textarea class="inputbox" cols="30" rows="4" name="dfcontact[infoText]"><?php echo ( !empty( $dfcontact["infoText"] ) ? htmlspecialchars( $dfcontact["infoText"] ) : "" ); ?></textarea></td>
			<td><?php echo JText::_( 'INFOTEXT_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'FORMTEXT') . ":" ?></td>
			<td><textarea class="inputbox" cols="30" rows="4" name="dfcontact[formText]"><?php echo ( !empty( $dfcontact["formText"] ) ? htmlspecialchars( $dfcontact["formText"] ) : "" ); ?></textarea></td>
			<td><?php echo JText::_( 'FORMTEXT_INFO') ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'BUTTONTEXT') . ":" ?></td>
			<td><input type="text" name="dfcontact[buttonText]" value="<?php echo ( !empty( $dfcontact["buttonText"] ) ? htmlspecialchars( $dfcontact["buttonText"] ) : "" ); ?>"></td>
			<td><?php echo JText::_( 'BUTTONTEXT_INFO') ?></td>
		</tr>
		</tbody>
		</table>
</fielset>

</td>
</tr>
</table>