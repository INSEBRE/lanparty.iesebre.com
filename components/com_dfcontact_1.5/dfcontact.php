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

// Load configuration data
include( JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_dfcontact' . DS . 'config.dfcontact.php' );

# Display com_securityimages captcha
define('DFCONTACT_CAPTCHA_ENABLED' , $dfcontact['captcha'] && file_exists(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_securityimages'));
if ( DFCONTACT_CAPTCHA_ENABLED && !empty( $_REQUEST["displayCaptcha"] ) ) {
	$check = null; 
	$mainframe->triggerEvent('onSecurityImagesDisplay', array($check)); 
	if (!$check) { 
		echo "Creation of captcha failed!"; 
	}
	exit;
}

# Display title
echo ( !empty( $dfcontact["pageTitle"] ) ? "<div class=\"contentheading\">" . $dfcontact["pageTitle"] . "</div>\n\n" : "" );

# Start content
echo "<div class=\"contentpane\">\n\n";

# Check existance of destination mail
if ( empty( $dfcontact["destinationMail"] ) ) {
	echo '<p>' . JText::_( 'FORM_NO_DESTINATION_MAIL') . "</p>\n\n";
}

# Start sending process if there is data
elseif ( !empty( $_REQUEST["submit"] ) ) {

	// create backlink
	$backlink = '<form action="" method="post">' . "\n";

	$dfcontact['field'] = ( is_array( $dfcontact['field'] ) ? $dfcontact['field'] : array() );
	reset( $dfcontact['field'] );
	while ( list( $key, $value ) = each( $dfcontact['field'] ) ) {
		if ( empty( $value['display'] ) ) {
			continue;
		}
		$backlink .= '<input type="hidden" name="' . htmlspecialchars( stripslashes( $key ) ) . '" value="' . ( !empty( $_REQUEST[$key] ) ? htmlspecialchars( stripslashes( $_REQUEST[$key] ) ) : '' ) . '" />' . "\n";
	}
	$backlink .= '<input type="hidden" name="submit" value="" />' . "\n";
	$backlink .= '<input type="submit" value="<< ' . JText::_( 'FORM_BACK') . '" class="button" />' . "\n";
	$backlink .= '</form>' . "\n";

	# check for neccessary vars
	$missingFields = '';
	$dfcontact['field'] = ( !empty( $dfcontact['field'] ) && is_array( $dfcontact['field'] ) ? $dfcontact['field'] : array() );
	reset( $dfcontact['field'] );
	while ( list( $key, $value ) = each ( $dfcontact['field'] )) {
		if ( !empty( $value['display'] ) && !empty( $value['duty'] ) && empty( $_REQUEST[$key] ) ) {
			$missingFields .= '<li>' . JText::_( $key ) . '</li>' . "\n";
		}
	}
	
	# captcha
	if (DFCONTACT_CAPTCHA_ENABLED) {
 		$captchaValid = null; 
        $captchaTest = JRequest::getVar('captcha', false, '', 'CMD'); 
        $mainframe->triggerEvent('onSecurityImagesCheck', array($captchaTest, &$captchaValid));
		if ( !$captchaValid ) {
			$missingFields .= '<li>' . JTEXT::_( 'FORM_CAPTCHA_ERROR') . "</li>\n";			
		}
	}
	
	# display errors
	if ( $missingFields ) {
		echo '<p>' . JText::_( 'FORM_MISSING_FIELDS') . '</p>' . "\n";
		echo "<ul>\n$missingFields</ul>\n";
		echo $backlink;
	} elseif ( !empty( $_REQUEST['email'] ) && !dfcontact_validMail( $_REQUEST['email'] ) ) {
		echo '<p>' . JText::_( 'FORM_ENTER_VALID_MAIL') . '</p>' . "\n";
		echo $backlink;
	} else {

		# create html with user vars
		$sentVars = '<table border="0">';
		$dfcontact['field'] = ( is_array( $dfcontact['field'] ) ? $dfcontact['field'] : array() );
		reset( $dfcontact['field'] );
		while( list( $key, $value ) = each( $dfcontact['field'] )) {
			if ( empty( $value['display'] ) || ( empty( $_REQUEST[$key] ) && $key != 'checkbox' ) ) {
				continue;
			}
			$sentVars .= '<tr>';
			$sentVars .= '<th valign="top" align="left">' . JText::_( $key ) . ':</th>' . "\n";
			$sentVars .= '<td valign="top">' . nl2br( htmlspecialchars( stripslashes( $key == 'checkbox' ? ( !empty( $_REQUEST[$key] ) && $_REQUEST[$key] ? JText::_( 'YES') : JText::_( 'NO') ) : ( !empty( $_REQUEST[$key]) ? $_REQUEST[$key] : '' ) ) ) ) . '</td>' . "\n";
			$sentVars .= '</tr>' . "\n";
		}
		$sentVars .= '</table>';

		# create html with server vars
		$serverVars = array(
			JText::_( 'FORM_DATE') 		=> date( JText::_( 'FORM_DATE_FORMAT') ),
			JText::_( 'FORM_SENT_URL') 	=> '<a href="' . 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '" target="_blank">' . 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '</a>',
			JText::_( 'FORM_USERAGENT')	=> $_SERVER['HTTP_USER_AGENT'],
			JText::_( 'FORM_HOST')		=> ($_SERVER['REMOTE_NAME'] ? $_SERVER['REMOTE_NAME'] : @gethostbyaddr( $_SERVER['REMOTE_ADDR'] )),
			JText::_( 'FORM_IP') 		=> $_SERVER['REMOTE_ADDR'],
			JText::_( 'FORM_PORT')		=> $_SERVER['REMOTE_PORT'],
		);
		$sentVarsHidden = '<table border="0">';
		reset( $serverVars );
		while( list( $key, $value ) = each( $serverVars ) ) {
			$sentVarsHidden .= '<tr>';
			$sentVarsHidden .= '<th valign="top" align="left">' . ucfirst( nl2br( htmlspecialchars( stripslashes( $key ) ) ) ) . ':</th>' . "\n";
			$sentVarsHidden .= '<td valign="top">' . nl2br( stripslashes( $value ) ) . '</td>' . "\n";
			$sentVarsHidden .= '</tr>' . "\n";
		}
		$sentVarsHidden .= '</table>';

		# create html part
		$html = "<html>\n";
		$html .= "<style type=\"text/css\">\n";
		$html .= "<!--\n";
		$html .= "body,td,th,p{font-family:verdana;font-size:12px;}\n";
		$html .= "//-->\n";
		$html .= "</style>\n";
		$html .= "<body>\n";
		$html .= '<p>' . JText::_( 'FORM_MAIL_TEXT') . '</p>' . "\n\n";
		$html .= "$sentVars\n";
		if ( !empty( $dfcontact['addServerData'] ) ) {
			$html .= '<p style="margin-top:2em;">' . JText::_( 'FORM_MAIL_TEXT_SERVER_VARS') . '</p>' . "\n\n";
			$html .= "$sentVarsHidden\n";
		}
		$html .= "</body>\n";
		$html .= "</html>";
		
		# create text part
		$text = trim( strip_tags( $html ) );
		$text = str_replace("\n ", "\n", $text);
		$text = "\n" . $text;
		
		function escapeSubject($string, $prefix="=?ISO-8859-1?Q?", $postfix="?=")    {
	        $crlf    = "\n\t";
	        $string  = preg_replace('!(\r\n|\r|\n)!', $crlf, $string) . $crlf ;
	        $f[]    = '/([\000-\010\013\014\016-\037\075\177-\377])/e' ;
	        $r[]    = "'=' . sprintf('%02X', ord('\\1'))" ;
	        $f[]    = '/([\011\040])' . $crlf . '/e' ;
	        $r[]    = "'=' . sprintf('%02X', ord('\\1')) . '" . $crlf . "'" ;
	        $string  = preg_replace($f, $r, $string);
	        return $prefix.trim(wordwrap($string, 70 - strlen($prefix) - strlen($postfix), ' ' . $postfix . $crlf . $prefix, true)).$postfix;
    	}	    
    
		# header vars
		$name = preg_replace('/[\r\n\t\f]/', '', ( !empty( $_REQUEST['name'] ) ? $_REQUEST['name'] : '' ) );
		$email = preg_replace('/[\r\n\t\f]/', '', ( !empty( $_REQUEST['email'] ) ? $_REQUEST['email'] : '' ) );
		$subject = ( function_exists( 'html_entity_decode') ? html_entity_decode( JText::_( 'FORM_MAIL_SUBJECT') ) : JText::_( 'FORM_MAIL_SUBJECT') ) . ' (' . preg_replace('/[\r\n\t\f]/', '', $_SERVER['SERVER_NAME']) . ')';
		$subject = escapeSubject($subject);
		$name = escapeSubject($name);
		
		# load internal mail class
		jimport('joomla.mail.mail');
		$mail = new JMail();
		switch ( $mainframe->getCfg('mailer') ) {
			case 'smtp':
				$mail->useSMTP( $mainframe->getCfg('smtpauth'), $mainframe->getCfg('smtphost'), $mainframe->getCfg('smtpuser'), $mainframe->getCfg('smtppass') );
				break;
			case 'sendmail':
				$mail->useSendmail( $mainframe->getCfg('sendmail') );
				break;
			default:
				break;
		}
		
		# Fill mail object with data
		$destinationEmails = split("[ ,;]", $dfcontact['destinationMail']);
		foreach ( $destinationEmails as $email ) {
			if ( $email != '' ) {
				$mail->addRecipient( $email );
			}
		}
		$mail->setSender( array( $mainframe->getCfg('mailfrom'), $mainframe->getCfg('fromname') ) );
		$mail->addReplyTo( array($email, $name) );
		$mail->setSubject( $subject );
		
		# Set mail body
		if ( !empty( $dfcontact['htmlMails'] ) ) {
			$mail->isHTML( true );
			$mail->Body = $html;
			$mail->AltBody = $text;
		} else {
			$mail->Body = $text;			
		}
		
		# Try to send email and display result
		$sendResult = $mail->Send();
		if ( is_bool($sendResult) && $sendResult == true ) {
			echo '<p class="dfContactSubmitSuccess">' . JText::_( 'FORM_MAIL_SUCCESS') .  '</p>' . "\n";
			if ( !empty( $dfcontact['displayUserInput'] ) ) {
				echo '<p class="dfContactSubmitVars">' . JText::_( 'FORM_MAIL_SUBMITTED_VARS') . '</p>' . "\n";
				echo $sentVars;
			}
		} else {
			echo '<p class="dfContactSubmitError">' . JText::_( 'FORM_MAIL_ERROR') . '</p>' . "\n";
			echo $backlink;
		}
	}
} else {

	// info text
	if ( !empty( $dfcontact['infoText'] ) ) {
		echo '<p class="dfContactInfoText">' . $dfcontact['infoText'] . '</p>' . "\n";
	}
	
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"dfContactTable\">\n";
		
	// address
	ob_start();
	$addressFormat = trim( $dfcontact['addressFormat']['templates'][$dfcontact['addressFormat']['selected']] );
	$addressFormatSplit = split("\n", $addressFormat);
	$lastCellEmpty = false;
	reset($addressFormatSplit);
	while (list($key, $line) = each($addressFormatSplit)) {
		
		// extract placeholders and get template string
		$placeholders = array();
		if (!preg_match_all('/\[([^\]]*)\]/', $line, $placeholders)) {
			if (!$lastCellEmpty) {
				$lastCellEmpty = true;
				echo "<tr><td>&nbsp;</td><td>" . trim($line) . "</td></tr>\n";
			}
			continue;
		}
		$placeholders = $placeholders[1];
		$line = preg_replace("/\{[^\}]*\}/", '', $line);
		
		// keys
		$keyCell = '';
		$nonDisplayableKeys = array(
			'company',
			'title',
			'name',
			'position',
			'addition',
			'street',
			'streetno',
			'postbox',
			'zip',
			'city',
			'state',
			'country',
		);
		$keys = array();
		foreach ($placeholders as $ph) {
			$ph = strtolower($ph);
			if (!in_array($ph, $nonDisplayableKeys)) {
				array_push($keys, trim(JText::_($ph)));
			}
		}
		$keyCell = join('/', $keys);		
		
		// values
		foreach ($placeholders as $ph) {
			
			$item = trim($dfcontact['field'][strtolower($ph)]['value']);
			
			// special fields
			if ($item) {
				switch ($ph) {
					case 'EMAIL':
						$item = ( !empty( $dfcontact['links']) ?  '<a href="mailto:' . $dfcontact['field']['email']['value'] . '">' . dfcontact_asciiEncodeString( $dfcontact['field']['email']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['email']['value'] ) );
						break;
					case 'ICQ':
						$item = ( !empty( $dfcontact['links']) ? '<a href="http://web.icq.com/whitepages/message_me?uin=' . $dfcontact['field']['icq']['value'] . '&action=message" target="_blank">' . dfcontact_asciiEncodeString( $dfcontact['field']['icq']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['icq']['value'] ) ) . ( !empty($dfcontact['onlineStatus']) ? ' <img src="http://status.icq.com/online.gif?web=' . $dfcontact['field']['icq']['value'] . '&img=5" align="absmiddle">' : '');
						break;
					case 'AIM':
						$item = ( !empty( $dfcontact['links'] ) ? '<a href="aim:goim?screenname=' . $dfcontact['field']['aim']['value'] . '&message">' . dfcontact_asciiEncodeString(  $dfcontact['field']['aim']['value'] ) . '</a>' : dfcontact_asciiEncodeString(  $dfcontact['field']['aim']['value'] ) );
						break;
					case 'YAHOO':
						$item = ( !empty( $dfcontact['links']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $dfcontact['field']['icq']['value'] . '&.src=pg" target="_blank">' . dfcontact_asciiEncodeString( $dfcontact['field']['yahoo']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['yahoo']['value'] ) ) . ( !empty($dfcontact['onlineStatus']) ? ' <img border=0 src="http://opi.yahoo.com/online?u=' . $dfcontact['field']['yahoo']['value'] . '&m=g&t=0&l=us"></a>' : '');
						break;
					case 'MSN':
						$item = dfcontact_asciiEncodeString(  $dfcontact['field']['msn']['value'] );
						break;
					case 'SKYPE':
						$item = ( !empty( $dfcontact['links']) ? '<script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script><a href="skype:' . $dfcontact['field']['skype']['value'] . '" onclick="return skypeCheck();">' . dfcontact_asciiEncodeString( $dfcontact['field']['skype']['value'] ) . '</a>' : dfcontact_asciiEncodeString( $dfcontact['field']['skype']['value'] ) ) . ( !empty($dfcontact['onlineStatus']) ? ' <img src="http://mystatus.skype.com/smallicon/' . $dfcontact['field']['skype']['value'] . '" style="border: none;" width="16" height="16" alt="My status" align="absmiddle" /></a>' : '');
						break;
				}
			}
			
			$line = str_replace("[$ph]", $item, $line);
		}
		$valueCell = trim($line);
		
		// display line
		if ($valueCell) {
			$lastCellEmpty = false;
			echo "<tr>\n";
			echo '<th valign="top">' . $keyCell . "&nbsp;</th>\n";
			echo '<td valign="top">' . $valueCell . "</td>\n";
			echo "</tr>\n";
		}
		
	}
	
	// prevent empty last row
	$emptyLine = '<tr><td>&nbsp;</td><td></td></tr>';
	$buffer = trim(ob_get_contents());
	if (substr($buffer, strlen($buffer) - strlen($emptyLine)) == $emptyLine) {
		$buffer = substr($buffer, 0, strlen($buffer) - strlen($emptyLine));
	}
	ob_end_clean();
	echo $buffer;
	echo '<tr><td>&nbsp;<br />&nbsp;</td><td></td></tr>' . "\n";

	// form text
	if ( !empty( $dfcontact['formText'] ) ) {
		echo '<tr><td colspan="2" class="dfContactFormText">' . $dfcontact['formText'] . '</td></tr>' . "\n";
		echo '<tr><td>&nbsp;</td><td></td></tr>' . "\n";
	}

	// contact form
	echo '<form action="" method="post" onsubmit="return dfcontact_checkForm();" id="dfContactForm" name="dfContactForm">' . "\n";
	$mandatoryFields = 0;
	$lastCellEmpty = false;
	reset($addressFormatSplit);
	while (list($key, $line) = each($addressFormatSplit)) {
		
		// extract placeholders and get template string
		$placeholders = array();
		if (!preg_match_all('/\[([^\]]*)\](\{.?\})?/', $line, $placeholders)) {
			if (!$lastCellEmpty) {
				$lastCellEmpty = true;
				echo "<tr>\n<td>&nbsp;</td><td>" . trim($line) . "</td></tr>\n";
			}
			continue;
		}
		$sizes = $placeholders[2];
		$placeholders = $placeholders[1];
		
		// keys
		$keyCell = '';
		$nonDisplayableKeys = array(
			'message',
			'checkbox',
			'mandatory',
		);
		$keys = array();
		foreach ($placeholders as $ph) {
			$ph = strtolower($ph);
			if (!in_array($ph, $nonDisplayableKeys)) {
				array_push($keys, '<label for="dfContactField-' . $ph . '">' . trim(JText::_($ph) . '</label>'));
			}
		}
		$keyCell = join('/', $keys);
		
		// calculate field sizes
		$fields = 0;
		$normalFields = 0;
		$specialsSizes = 0;
		$fullSize = 15;
		for ($i = 0; $i < sizeof($placeholders); $i++) {
			
			$ph = strtolower($placeholders[$i]);
			$specialSize = str_replace('{', '', str_replace('}', '', $sizes[$i]));
			
			if ($dfcontact['field'][$ph]['display'] && !$specialSize) {
				$normalFields++;
				$fields++;
			} else if ($dfcontact['field'][$ph]['display'] ) {
				$specialsSizes += $specialSize;	
				$fields++;			
			}
		}
		if ($normalFields) {
			$normalSize = ($fullSize - $specialsSizes - (($fields - 1) * (strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') > 0 ? 0.3 : 0.4))) / $normalFields;
			$normalSize = number_format($normalSize, 2, ".", "");
		} else {
			$normalSize = 15;
		}
		
		// values
		$phs = array();
		$line = '';
		for ($i = 0; $i < sizeof($placeholders); $i++) {
			
			$ph = strtolower($placeholders[$i]);
			$specialSize = str_replace('{', '', str_replace('}', '', $sizes[$i]));
			$item = trim($dfcontact['field'][$ph]['value']);
			
			if ($dfcontact['field'][$ph]['display']) {
				array_push($phs, $ph);
				if ( !empty( $dfcontact['field'][$ph]['duty'] ) ) {
					$mandatoryFields++;
				}
				switch($ph) {
					case 'message' :
						$line .= '<textarea name="message" id="dfContactField-message" class="inputbox" cols="40" rows="5">' . ( !empty( $_REQUEST['message'] ) ? htmlspecialchars( stripslashes( $_REQUEST['message'] ) ) : '' ) . '</textarea>' . ( !empty( $dfcontact['field'][$ph]['duty'] ) ? '*' : '');
						break;
					case 'checkbox' :
						$line .= '<input type="checkbox" name="checkbox" id="dfContactField-checkbox" class="inputbox"' . ( !empty( $_REQUEST['checkbox'] ) ? ' checked="checked"' : '' ) . ' /> <label for="dfContactField-' . $ph . '">' . $dfcontact['field']['checkbox']['text'] . '</label>' . ( !empty( $dfcontact['field'][$ph]['duty'] ) ? ' *' : '');
						break;
					default:
						$line .=  '<input type="text" name="' . $ph . '" id="dfContactField-' . $ph . '" class="inputbox" value="' . ( !empty( $_REQUEST[$ph] ) ? htmlspecialchars( stripslashes( $_REQUEST[$ph] ) ) : "" ) . '" style="width:' . ($specialSize ? $specialSize : $normalSize) . 'em;" />'  . ( !empty( $dfcontact['field'][$ph]['duty'] ) ? '*' : '');
				}
			} elseif ( $ph == 'captcha' && DFCONTACT_CAPTCHA_ENABLED ) {
				array_push($phs, $ph);
				$mandatoryFields++;
				$captchaImageUrl = 'index.php?option=com_dfcontact&displayCaptcha=true';
				$line .= JTEXT::_( 'FORM_CAPTCHA_INFO') . '<br /><img src="' . $captchaImageUrl . '" id="dfContactField-captcha-image" /> <img src="components/com_securityimages/buttons/reload.gif" border="0" onclick="var date = new Date();document.getElementById(\'dfContactField-captcha-image\').src=\'' . $captchaImageUrl . '&timestamp=\' + date.valueOf();" style="cursor:pointer;" /><br /><input type="text" name="' . $ph . '" id="dfContactField-' . $ph . '" style="width:' . ($specialSize ? $specialSize : $normalSize) . 'em;" />*';
			} elseif ( $ph == 'mandatory' ) {
				$line .= JText::_( 'FORM_MANDATORY');
			}
	
		}
		$valueCell = trim($line);
		
		// display line
		if ($valueCell) {
			$lastCellEmpty = false;
			echo "<tr>\n";
			echo '<th valign="top">' . $keyCell . "&nbsp;</th>\n";
			echo '<td valign="top">' . $valueCell;
			foreach ($phs as $ph) {
				echo "<span id=\"dfContactFieldErrorSpan-$ph\" class=\"dfContactError\"></span>\n";			
			}
			echo "</td>\n";
			echo "</tr>\n";
		}	
		
	}
	
	// submit button & form end
	echo "<tr>\n";
	echo "<td></td><td>&nbsp;</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo '<td></td><td><input type="submit" value="' . ( !empty( $dfcontact['buttonText'] ) ? $dfcontact['buttonText'] : JText::_('FORM_SUBMIT') ) . '" class="button" /></td>' . "\n";
	echo "</tr>\n";
	echo '<input type="hidden" name="submit" value="true" />' . "\n";
	echo "</form>\n";
	echo "</table>\n";
?>

<span style="display:none;visibility:hidden;">Created using the Joomla DFContact component.</span>

<script type="text/javascript">
<!--

function DFContactValidation(id, duty) {
	this.id		= id;
	this.duty 	= duty;
}


function dfcontact_checkForm() {

  var fields = new Array();
<?php
reset($dfcontact['field']);
while(list($key, $value) = each($dfcontact['field'])) {
	echo "  fields.push(new DFContactValidation('" . $key . "', " . ($value['duty'] ? 'true' : 'false') . "));\n";
}
if ( DFCONTACT_CAPTCHA_ENABLED ) {
	echo "  fields.push(new DFContactValidation('captcha', true));\n";
}
?>

  // check fields
  var errorSpan;
  var node;
  var nodeSelected;
  var ok = true;
  for (var i = 0; i < fields.length; i++) {
  	errorSpan = document.getElementById('dfContactFieldErrorSpan-' + fields[i].id);
  	node = document.getElementById('dfContactField-' + fields[i].id);
  	if (errorSpan && node) {
  		if (fields[i].duty
  			&& (
  				(node.nodeName == 'INPUT' && node.getAttribute('type') == 'text' && node.value == '')
  				|| (node.nodeName == 'INPUT' && node.getAttribute('type') == 'checkbox' && !node.checked)
  				|| (node.nodeName == 'TEXTAREA' && node.value == '')
  				)
  			) {
			ok = false;
	  		errorSpan.innerHTML = '<br />' + ( node.getAttribute('type') == 'checkbox' ? '<?php echo JText::_( 'FORM_PLEASE_CHECK_BOX' ); ?>' : '<?php echo JText::_( 'FORM_PLEASE_FILL_FIELD' ); ?>');
	  		if (!nodeSelected) {
	  			nodeSelected = node;
	  			node.focus();
	  		}
  			
  		} else {
	  		errorSpan.innerHTML = '';
  		}
  	}
  }
	
  return ok;
}

function validMail(s) {
  var a = false;
  var res = false;
  if ( typeof( RegExp ) == 'function') {
    var b = new RegExp( 'abc' );
    if ( b.test( 'abc' ) == true ) {
      a = true;
    }
  }
  if ( a == true ) {
    reg = new RegExp( '^([a-zA-Z0-9\\-\\.\\_]+)' +
                   '(\\@)([a-zA-Z0-9\\-\\.ÄäÜüÖö]{2,255})' +
                   '(\\.)([a-zA-Z]{2,6})$' );
    res = ( reg.test( s ) );
  } else {
    res = ( s.search( '@' ) >= 1 &&
    s.lastIndexOf( '.' ) > s.search( '@' ) &&
    s.lastIndexOf( '.' ) >= s.length - 5 )
  }
  return( res );
}
//-->
</script>
	
<?php
}

# End content
echo "\n</div>\n";






/**
 * Encodes a string into it's asci-entities.
 *
 * @static
 * @access	public
 * @param	string	$string
 * @return	string
 * @date	13:42 13.10.2005
 * @version	1.0
 * @status	Complete
 */
function dfcontact_asciiEncodeString( $string ) {

	$result = "";

	for ( $i = 0; $i < strlen( $string ); $i++ ) {
		$result .= "&#" . ord( $string[$i] ) . ";";
	}

	return $result;
}



/**
 * Returns whether an email-adress or
 * a list of comma-separated email-adresses
 * is valid and has a valid tld (optional).
 *
 * @static
 * @access	public
 * @param	string	$eMail		eMail
 * @param	boolean $checkTLD 	Check TLD-Validity?
 * @return	boolean
 * @date	16:20 01.07.2005
 * @version	1.0
 * @status	Complete
 */
function dfcontact_validMail($eMail, $checkTLD = false) {

	# list taken from: http://data.iana.org/TLD/tlds-alpha-by-domain.txt
	# Version 1.2, Last Updated 2005-04-29
	$tlds = array("AC", "AD", "AE", "AERO", "AF", "AG", "AI", "AL", "AM", "AN", "AO", "AQ", "AR", "ARPA", "AS", "AT", "AU", "AW", "AZ", "BA", "BB", "BD", "BE", "BF", "BG", "BH", "BI", "BIZ", "BJ", "BM", "BN", "BO", "BR", "BS", "BT", "BV", "BW", "BY", "BZ", "CA", "CC", "CD", "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN", "CO", "COM", "COOP", "CR", "CU", "CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO", "DZ", "EC", "EDU", "EE", "EG", "ER", "ES", "ET", "EU", "FI", "FJ", "FK", "FM", "FO", "FR", "GA", "GB", "GD", "GE", "GF", "GG", "GH", "GI", "GL", "GM", "GN", "GOV", "GP", "GQ", "GR", "GS", "GT", "GU", "GW", "GY", "HK", "HM", "HN", "HR", "HT", "HU", "ID", "IE", "IL", "IM", "IN", "INFO", "INT", "IO", "IQ", "IR", "IS", "IT", "JE", "JM", "JO", "JP", "KE", "KG", "KH", "KI", "KM", "KN", "KR", "KW", "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR", "LS", "LT", "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MIL", "MK", "ML", "MM", "MN", "MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MUSEUM", "MV", "MW", "MX", "MY", "MZ", "NA", "NAME", "NC", "NE", "NET", "NF", "NG", "NI", "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "ORG", "PA", "PE", "PF", "PG", "PH", "PK", "PL", "PM", "PN", "PR", "PRO", "PS", "PT", "PW", "PY", "QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD", "SE", "SG", "SH", "SI", "SJ", "SK", "SL", "SM", "SN", "SO", "SR", "ST", "SU", "SV", "SY", "SZ", "TC", "TD", "TF", "TG", "TH", "TJ", "TK", "TL", "TM", "TN", "TO", "TP", "TR", "TT", "TV", "TW", "TZ", "UA", "UG", "UK", "UM", "US", "UY", "UZ", "VA", "VC", "VE", "VG", "VI", "VN", "VU", "WF", "WS", "YE", "YT", "YU", "ZA", "ZM", "ZW");

	$eMails = split( ",", str_replace( ", ", ",", $eMail ) );

	for ( $i = 0; $i < sizeof( $eMails ); $i++ ) {
		# check format
		if ( !preg_match( "/^([a-zA-Z0-9\\+\\-\\._])+@([a-zA-Z0-9ÄäÜüÖö\\-]{2,255}\\.)+([a-zA-Z]){2,6}$/" , $eMails[$i] ) ) {
			return false;
		}
		# check tld
		if( $checkTLD && !in_array( strtoupper( substr( $eMails[$i], strrpos( $eMails[$i], "." ) + 1 ) ), $tlds ) ) {
			return false;
		}
	}

	return true;

}

?>