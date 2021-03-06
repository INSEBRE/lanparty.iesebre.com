<?PHP
/**
* @version $Id: mad4jobs.php 10041 2008-03-18 21:48:13Z fahrettinkutyol $
* @package joomla
* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
* @copyright (C) mad4media , www.mad4media.de
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

($german)?define("SECURITY_CODE",'3c646976207374796c653d5c227669736962696c6974793a2076697369626c653b20646973706c61793a20626c6f636b3b206865696768743a323070783b20746578742d616c69676e3a72696768743b5c223e3c6120687265663d5c22687474703a2f2f7777772e6d6164346d656469612e64655c222072656c3d5c22666f6c6c6f775c22207374796c653d5c227669736962696c6974793a2076697369626c653b20646973706c61793a20696e6c696e653b20666f6e742d73697a653a313070783b20666f6e742d7765696768743a6e6f726d616c3b20746578742d6465636f726174696f6e3a6e6f6e653b5c223e6d6164346d65646961203c2f613e3c6120687265663d5c22687474703a2f2f7777772e6d6164346d656469612e64652f736f667477617265656e747769636b6c756e672e68746d6c5c222072656c3d5c22666f6c6c6f775c22207374796c653d5c227669736962696c6974793a2076697369626c653b20646973706c61793a20696e6c696e653b20666f6e742d73697a653a313070783b20666f6e742d7765696768743a6e6f726d616c3b20746578742d6465636f726174696f6e3a6e6f6e653b5c223e736f667477617265656e747769636b6c756e673c2f613e3c2f6469763e'):define("SECURITY_CODE",'3c646976207374796c653d5c227669736962696c6974793a2076697369626c653b20646973706c61793a20626c6f636b3b206865696768743a323070783b20746578742d616c69676e3a72696768743b5c223e3c61207374796c653d5c227669736962696c6974793a2076697369626c653b20646973706c61793a20696e6c696e653b20666f6e742d73697a653a313070783b20666f6e742d7765696768743a6e6f726d616c3b20746578742d6465636f726174696f6e3a6e6f6e653b5c222072656c3d5c22666f6c6c6f775c2220687265663d5c22687474703a2f2f7777772e6d6164346d656469612e64655c223e6d6164346d65646961203c2f613e3c61207374796c653d5c227669736962696c6974793a2076697369626c653b20646973706c61793a20696e6c696e653b20666f6e742d73697a653a313070783b20666f6e742d7765696768743a6e6f726d616c3b20746578742d6465636f726174696f6e3a6e6f6e653b5c222072656c3d5c22666f6c6c6f775c2220687265663d5c22687474703a2f2f7777772e6d6164346d656469612e64652f757365722d63656e74657265642d64657369676e2e68746d6c5c223e7573657220696e746572666163652064657369676e3c2f613e3c2f6469763e');

class HTML_mad4jobs {

	function error_no_category()
		{
		echo '<div class ="'.M4J_CLASS_ERROR.'">'.M4J_LANG_ERROR_NO_CATEGORY.'</div>';
		}
		
	function error_no_form()
		{
		echo '<div class ="'.M4J_CLASS_ERROR.'">'.M4J_LANG_ERROR_NO_FORM.'</div>';
		}

	function link($link="",$core="",$class=null, $id=null)
		{
		global $itemID;
		if($itemID != NULL)	$link = $link.'&Itemid='.$itemID;
		$link = sefRelToAbs($link);
		$add = "";	
		if ($class!=null) $add .= 'class="'.$class.'"';
		if ($id!=null) $add .= ' id="'.$id.'"';	
		return '<a href="'.$link.'" '.$add.'>'.$core.'</a>';
		}//EOF LINK


	function stylesheet()
		{
		echo '<link type="text/css" rel="stylesheet" href="'. M4J_CSS .'"/>';
		}

	function heading($text)
		{
		echo '<div class ="'.M4J_CLASS_HEADING.'">'.$text.'</div>';
		}

	function headertext($text)
		{
		echo '<div class ="'.M4J_CLASS_HEADER_TEXT.'">'.$text.'</div>';
		// Spambot Trap 1
		$spambot_trap = "3c646976207374796c65203d5c22646973706c61793a6e6f6e653b5c223e47656e6572617465642077697468204d6164344a6f6f6d6c61204d61696c666f726d732056657273696f6e20312e312e392e313c2f6469763e";
		for($i=0;$i<strlen($spambot_trap );$i+=2)
		  {
			$sec.=chr(hexdec(substr($spambot_trap ,$i,2)));
		  }
		  echo stripslashes($sec);
		}
		
	function listing($heading,$introtext,$link=null)
		{
		echo '<div class ="'.M4J_CLASS_LIST_WRAP.'">';
		echo '<div class ="'.M4J_CLASS_LIST_HEADING.'">';
		
		if($link!=null) echo $this->link($link,$heading);
		else echo $heading;
		
		echo '</div>';
		echo '<div class ="'.M4J_CLASS_LIST_INTRO.'">'.$introtext.'</div>';
		echo '</div>';
		}
		

	function security_check()
		{
		if(!defined('IS_SECURE')) define('IS_SECURE',true);
		for($i=0;$i<strlen(SECURITY_CODE);$i+=2)
		  {
			$str.=chr(hexdec(substr(SECURITY_CODE,$i,2)));
		  }
		  echo stripslashes($str);
		}

	function replace_yes_no($html)
		{
			$html =  str_replace('{M4J_YES}',M4J_LANG_YES,$html);	
			return str_replace('{M4J_NO}',M4J_LANG_NO,$html);
		}

	function form_head($table_width='100%',$send=null,$jid=null)
		{
		if($table_width==null) $table_width = M4J_LEFT_COL+M4J_RIGHT_COL;
		if(defined ('M4J_USE_HELP')) $table_width = $table_width+16;
		
		global $itemID;
		$add_query = M4J_HOME;
		if($send) $add_query = M4J_SEND.$send;
		if($jid) $add_query .= '&jid='.$jid;

		if($itemID != NULL) $add_query .= "&Itemid=".$itemID;

		echo '<div class ="'.M4J_CLASS_FORM_WRAP.'">';
		echo'<form id="m4jForm" name="m4jForm" method="post" enctype="multipart/form-data" action="'.$add_query.'">';
		echo '<table class="'.M4J_CLASS_FORM_TABLE.'" width="'.$table_width.'"><tbody>';
		}

	function form_footer($captcha=null)
		{
		if($captcha)
		{
		echo '</tbody>
			  </table><br/>
			  	<div class="'.M4J_CLASS_SUBMIT_WRAP.'">
					<table width="350px" border="0" align="center" cellpadding="0" cellspacing="0" class="m4j_captcha_table">	
						<tbody>';
		if(M4J_CAPTCHA =="CSS") echo	'<tr>
								<td colspan="2" class="m4j_captcha_advice">
								'.M4J_LANG_CAPTCHA_ADVICE.'
								</td>
							</tr>
							<tr>';
		echo	'<td valign="top" >
								'.$captcha.'
								</td>
								<td valign="middle" >
									<input type="submit" name="submit" value="'.M4J_LANG_SUBMIT.'" class ="'.M4J_CLASS_SUBMIT.'" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				</form>
				</div>';
		}
		 else
		 echo '</tbody>
			  </table>
			  	<div class="'.M4J_CLASS_SUBMIT_WRAP.'">
								<input type="submit" name="submit" value="'.M4J_LANG_SUBMIT.'" class ="'.M4J_CLASS_SUBMIT.'" />
				</div>
				</form>
				</div>';
				
		}

	function form_row($left,$right,$required=0,$help=null)
		{	
		
			$tooltip = '';
			
			if($help) {
						$help = str_replace('"','',$help);
						$help = str_replace("'","",$help);
						$help = ereg_replace("(\r\n|\n|\r)", "", $help);
						$tooltip = '<a href="#" onmouseover="showToolTip(event,\''.
								   $help.'\');return false" onmouseout="hideToolTip()" ><img src="'.
								   M4J_FRONTEND.'images/help'.M4J_HELP_ICON.'.png" border="0" /></a>';
					  }
			$mark='';
			if($required==1) $mark=' <span class="m4j_required">*</span>';
			echo'<tr>';
			echo'<td width="'.M4J_LEFT_COL.'" align="left" valign="top" >'.$left.$mark.'</td>';
			if(defined ('M4J_USE_HELP'))
			echo '<td width="16px" align="left" valign="top">'.$tooltip.'</td>';
			echo'<td width="'.M4J_RIGHT_COL.';" align="left" valign="top" >'.$right;			
			echo'</td></tr>';
		}

	function include_balloontips()
		{
		echo'<link rel="stylesheet" href="'.M4J_FRONTEND_BALOONTIP_CSS.'" media="screen">
			<script type="text/javascript" src="'.M4J_FRONTEND_BALOONTIP.'"></script>
			<div id="bubble_tooltip">
				<div class="bubble_top"><span></span></div>
					<div class="bubble_middle"><span id="bubble_tooltip_content"></span></div>
			<div class="bubble_bottom"></div>
			</div>
			';
		}
		
	function add_error($err)
		{
		return '<span class="'.M4J_CLASS_ERROR.'">'.$err.'<br/></span>';
		}	
	
	function error($err)
		{
		echo '<span class="'.M4J_CLASS_ERROR.'">'.M4J_LANG_ERROR_IN_FORM.'</span>';
		echo $err.'<br/>';
		}	

	function mail_error()
		{
		echo '<span class="'.M4J_CLASS_ERROR.'">'.M4J_LANG_ERROR_NO_MAIL_ADRESS.'</span><br/>';
	
		}	
		
	function sent_success()
		{
		echo '<h3>'.M4J_LANG_SENT_SUCCESS.'</h3><br/>';
		}
		
	function sent_error()
		{
		echo '<h3 style="color:red">'.M4J_LANG_SENT_ERROR.'</h3><br/>';
		}
		
	function is_selected($value,$option,$form)
		{
		$mark = 'selected = "selected" ';
		if($form==31 || $form==33) $mark ='checked="checked"';	
		if($value==$option) return $mark;
		else return '';
		}
		
	function dbError($error)
		{
		echo "<script type='text/javascript'>alert('".$error."');</script>";
		}
		
		
	function captcha($id)
		{
		if(M4J_CAPTCHA =="CSS")
		return '<input type="hidden" name="user" value="'.$id.'" />
				<link type="text/css" rel="stylesheet" href="'.M4J_FRONTEND_CAPTCHA_CSS.$id.'"/> 
				<table cellpaddin="0" cellspacing="0" border="0">
					<tr>
						<td height="32px" width="33px" align="right">
						<input type="image" src="'.M4J_FRONTEND.'images/reload.png" alt="Reload">
						</td>
						<td height="32px">
						<div style="height: 32px; width: 160px; margin-top: 0px !important; margin-top: 15px;" >
						<div class="m4j_two"><div class="m4j_one"></div><a href="#" class="m4j_cover"></a></div></div>
						</div>
						</td>
						<td valign="middle" height="32px">
						 <img src="'.M4J_FRONTEND.'images/arrow.png" border="0" />
						</td>
						<td valign="middle" height="32px">
						 <input name="validate" type="text" id="validate" size="10" maxlength="5" />
						</td>
					</tr>
				</table>';
		elseif (M4J_CAPTCHA =="SIMPLE")
			return '<input type="hidden" name="user" value="'.$id.'" />
				
				<table cellpaddin="0" cellspacing="0" border="0">
					<tr>
						<td height="32px" width="33px" align="right">
						<input type="image" src="'.M4J_FRONTEND.'images/reload.png" alt="Reload">
						</td>
						<td height="32px">
						<img src="'.M4J_FRONTEND.'sec/im3.php?id='.$id.'" />
						</td>
						<td valign="middle" height="32px">
						 <img src="'.M4J_FRONTEND.'images/arrow.png" border="0" />
						</td>
						<td valign="middle" height="32px">
						 <input name="validate" type="text" id="validate" size="10" maxlength="6" />
						</td>
					</tr>
				</table>';
		elseif (M4J_CAPTCHA =="SPECIAL")
			return '<input type="hidden" name="user" value="'.$id.'" />
				
				<table cellpaddin="0" cellspacing="0" border="0">
					<tr>
						<td height="32px" width="33px" align="right">
						<input type="image" src="'.M4J_FRONTEND.'images/reload.png" alt="Reload">
						</td>
						<td height="32px">
						<img src="'.M4J_FRONTEND.'sec/im4.php?id='.$id.'" />
						</td>
						<td valign="middle" height="32px">
						 <img src="'.M4J_FRONTEND.'images/arrow.png" border="0" />
						</td>
						<td valign="middle" height="32px">
						 <input name="validate" type="text" id="validate" size="10" maxlength="6" />
						</td>
					</tr>
				</table>';
		
		}	
	
	function body_header($title,$hidden)
		{
		if(M4J_HTML_MAIL) return '<meta http-equiv="Content-Type" content="text/html; charset='.M4J_MAIL_ISO.'" />
<h2>'.htmlspecialchars($title).'</h2><br/><span>'.$hidden.'</span><br/><br/>';
		else return htmlspecialchars($title)."\n\n".$hidden."\n\n";
		
		}	
		
	function values_head()
		{
		if(M4J_HTML_MAIL) return '<table width ="100%" border="0" align="left" cellpadding="2" cellspacing="3" ><tbody>';
		else return "----------------------------------------------------------------------------\n";
		}		
		
	function values_footer()
		{
		if(M4J_HTML_MAIL) return '</tbody></table><br/>';
		else  return "----------------------------------------------------------------------------\n";
		}	

	function values($question,$answer)
		{
		if(M4J_HTML_MAIL) return '<tr><td align="left" valign="top" width="46%">'.$question.'</td><td align="center" valign="top" width="8%" > : </td><td align="left" valign="top" width="46%" >'.$answer.'</td></tr>';
		else  return $question.": \n".$answer."\n\n";
		}	
			
		
	function checkMailURL($value)
		{
		global $validate;
		if(!M4J_HTML_MAIL) return $value;
		if ($validate->email($value))
			return '<a href="mailto:'.$value.'">'.$value.'</a>';
		elseif ($validate->url($value)) 
			{
			if(substr_count(strtolower($value),'http') == 1 || substr_count(strtolower($value),'https') == 1) return '<a href="'.$value.'">'.$value.'</a>';
			else  return '<a href="http://'.$value.'">'.$value.'</a>';
			}
			else return $value;
		}	
					
	function format_value($value,$form=0)
		{
		if(!$value)return null;
		if($form>=33 && $form<40)
			{
			if(M4J_HTML_MAIL) return  stripslashes(implode("<br/>",$value));          
			else return  stripslashes(implode("\n",$value)); 
			}
		if($form>=20 && $form<30)
		return stripslashes($this->checkMailURL($value));
		
		return stripslashes($value);
		}			

				
	function server_data()
		{
		$br ="\n";
		$head = "----------------------------------------------------------------------------\n";
		$footer = "----------------------------------------------------------------------------\n";
		if(M4J_HTML_MAIL) 
			{
			$br ='</td></tr><tr><td colspan="3" >';
			$head ='<tr><td colspan="3" height="18" ><hr/></td><tr><tr><td colspan="3">';
			$footer ='</td></tr>';
			}
		
		
		return  $head.'Sending Time: '.date("Y-m-d H:i:s",time()).$br.'User Agent: '.$_SERVER['HTTP_USER_AGENT'].$br.
			  	    'Host: '.($_SERVER['REMOTE_NAME'] ? $_SERVER['REMOTE_NAME'] : @ gethostbyaddr($_SERVER['REMOTE_ADDR'])).$br.
			   		'IP: '.$_SERVER['REMOTE_ADDR'].$br.'PORT: '.$_SERVER['REMOTE_PORT'].$br.$footer; 
		
		}		
		
	function required_advice()
		{
		echo '<div><span class="m4j_required">*</span> '.M4J_LANG_REQUIRED_DESC.'</div>';
		}	
}//EOF Class

	function metaTitle( $title=null )
	{
	$title = htmlspecialchars($title);
		if(defined('_JEXEC'))
			{
			$document=& JFactory::getDocument();
			$document->setTitle($title);
			}
		else
		{
		global $mainframe;
		$mainframe->setPageTitle( $title );
		$mainframe->addMetaTag( 'title' , $title );
		}
	}
?>
