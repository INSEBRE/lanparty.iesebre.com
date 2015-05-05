<?php
/**
 * @version		$Id: example.php 10714 2008-08-21 10:10:14Z eddieajau $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Example Content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.5
 */
class plgContentCkForms extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgContentCkForms( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		JPlugin::loadLanguage( 'plg_content_ckforms' );
		
	}

	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 */
	function onPrepareContent( &$article, &$params, $limitstart )
	{
		global $mainframe;

		$startpos = 0;
		$stringToSearchStart = "{ckform";
		$stringToSearchEnd = "}";
		$posStart = strpos ($article->text ,$stringToSearchStart,$startpos);
		
		while ($posStart != false)
		{
			$iposEnd = strpos ($article->text ,$stringToSearchEnd,$startpos);
			if ($iposEnd != false)
			{
				$_SESSION['ck_send_once'] = "1";
				
				$ckFormTag = substr ($article->text, $posStart,$iposEnd - $posStart + 1);
				$textFound = substr ($article->text, $posStart + strlen($stringToSearchStart),$iposEnd-$posStart-strlen($stringToSearchStart));
				$textFound = trim($textFound);
				
				// Load Selected form
				$form = $this->loadCkForm($textFound);
				$fields = $this->loadCkFormFields($form->id);
				
				$this->formLink = "index.php?option=com_ckforms&view=ckforms&task=send&id=".$form->id;		
				
				$html = $this->generateCkFormHTML( $form, $fields);
				
				$article->text = str_replace($ckFormTag, $html, $article->text);
			}

			$posStart = strpos ($article->text ,"{ckform",$posStart+strlen($stringToSearchStart));
			//$sections=split("{ckform[/]?e>", $article->text);
		}

		
	}

	/**
	 * Example after display title method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 * @return	string
	 */
	function loadCkForm($name)
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__ckforms where name='".$name."'";
						
		$db->setQuery( $query );
		$data = $db->loadObject();

		return $data;
	}

	/**
	 * Example before display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 * @return	string
	 */
	function loadCkFormFields($id)
	{
		$db =& JFactory::getDBO();
		$query = ' SELECT * FROM #__ckfields where fid='.$id." and published=1 order by ordering asc" ;
		$db->setQuery($query);
		$fields = $db->loadObjectList();
		
		$n=count($fields);
		for ($i=0; $i < $n; $i++)
		{ 
			$opt = explode("[--]", $fields[$i]->defaultvalue);
			
			switch ($fields[$i]->typefield)
			{
				case 'text':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueT = $key1[1];
					} else {
						$fields[$i]->t_initvalueT = '';
					}
					if (count($opt) > 1) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_maxchar = $key2[1];
					} else {
						$fields[$i]->t_maxchar = '';
					}
					if (count($opt) > 2) {
						$key3 = explode("===", $opt[2]);
						$fields[$i]->t_texttype = $key3[1];	
					} else {
						$fields[$i]->t_texttype = '';
					}
					if (count($opt) > 3) {
						$key4 = explode("===", $opt[3]);
						$fields[$i]->t_minchar = $key4[1];		
					} else {
						$fields[$i]->t_minchar = '';
					}
					break;
	
				case 'hidden':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueH = $key1[1];
					} else {
						$fields[$i]->t_initvalueH = '';
					}
						
					break;
					
				case 'textarea':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueTA = $key1[1];
					} else {
						$fields[$i]->t_initvalueTA = '';
					}
					if (count($opt) > 1) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_HTMLEditor = $key2[1];
					} else {
						$fields[$i]->t_HTMLEditor = '';
					}
					if (count($opt) > 2) {
						$key3 = explode("===", $opt[2]);
						$fields[$i]->t_columns = $key3[1];
					} else {
						$fields[$i]->t_columns = '';
					}
					if (count($opt) > 3) {
						$key4 = explode("===", $opt[3]);
						$fields[$i]->t_rows = $key4[1];
					} else {
						$fields[$i]->t_rows = '';
					}
					if (count($opt) > 4) {
						$key5 = explode("===", $opt[4]);
						$fields[$i]->t_wrap = $key5[1];					
					} else {
						$fields[$i]->t_wrap = '';
					}
					if (count($opt) > 5) {
						$key6 = explode("===", $opt[5]);
						$fields[$i]->t_maxchar = $key6[1];
					} else {
						$fields[$i]->t_maxchar = '';
					}
					if (count($opt) > 6) {
						$key7 = explode("===", $opt[6]);
						$fields[$i]->t_minchar = $key7[1];							
					} else {
						$fields[$i]->t_minchar = '';
					}

					break;
	
				case 'checkbox':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_initvalueCB = $key1[1];
					} else {
						$fields[$i]->t_initvalueCB = '';
					}
					if (count($opt) > 0) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_checkedCB = $key2[1];										
					} else {
						$fields[$i]->t_checkedCB = '';
					}
					break;
					
				case 'radiobutton':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_listHRB = $key1[1];
					} else {
						$fields[$i]->t_listHRB = '';
					}
					break;

				case 'select':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_multipleS = $key1[1];
					} else {
						$fields[$i]->t_multipleS = '';
					}
					if (count($opt) > 1) {
						$key2 = explode("===", $opt[1]);
						$fields[$i]->t_heightS = $key2[1];
					} else {
						$fields[$i]->t_heightS = '';
					}
					if (count($opt) > 2) {
						$key3 = explode("===", $opt[2]);
						$fields[$i]->t_listHS = $key3[1];					
					} else {
						$fields[$i]->t_listHS = '';
					}
					break;

				case 'button':
					if (count($opt) > 0) {
						$key1 = explode("===", $opt[0]);
						$fields[$i]->t_typeBT = $key1[1];
					} else {
						$fields[$i]->t_typeBT = '';
					}
					break;
			}					
		
		}				
		
		return $fields;
	}

	/**
	 * Example after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 * @return	string
	 */
	function generateCkFormHTML( $form, $fields)
	{
		global $mainframe;

		$plugin	=& JPluginHelper::getPlugin('content', 'ckforms');
		$pluginParams = new JParameter( $plugin->params );
		$displaytitle = $pluginParams->get( 'displaytitle', '1');
		$pageclass_sfx = $pluginParams->get( 'pageclass_sfx', '');

		$html = '<link rel="stylesheet" href="'.JURI::root(true).'/components/com_ckforms/css/ckforms.css" type="text/css" />'."\n";
		$html = $html.'<link rel="stylesheet" href="'.JURI::root(true).'/components/com_ckforms/css/ui.datepicker.css" type="text/css" />'."\n";

		$html = $html.'<script type="text/javascript" src="'.JURI::root(true).'/components/com_ckforms/js/jquery-1.3.2.min.js"></script>'."\n";
		$html = $html.'<script type="text/javascript" src="'.JURI::root(true).'/components/com_ckforms/js/jquery.tooltip.min.js"></script>'."\n";
		$html = $html.'<script type="text/javascript" src="'.JURI::root(true).'/components/com_ckforms/js/ui.datepicker.packed.js"></script>'."\n";
		$html = $html.'<script type="text/javascript" src="'.JURI::root(true).'/components/com_ckforms/js/jquery.validate.pack.js"></script>'."\n";

		$nbFields=count($fields);

		if (strcmp ($displaytitle , "1" ) == 0) 
		{
			$html = $html.'<div class="componentheading'.$pageclass_sfx.'">'.$form->title.'</div>'."\n";
		}
		
		$lastFieldsToValidate = "";
		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $fields[$i];
			if ($field->mandatory == "1" || ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != ''))) {
				$lastFieldsToValidate = $field->name;
			}
		}
		
		$html = $html.'<script type="text/javascript">'."\n";
		
		$html = $html.'var JNC_jQuery = jQuery.noConflict();'."\n";
		$html = $html.'JNC_jQuery(function() {'."\n";
		
		$html = $html.'	JNC_jQuery(".ckform_tooltip").tooltip({'."\n";
		$html = $html.'		track: true,'."\n";
		$html = $html.'		showURL: false,'."\n";
		$html = $html.'		fade: 250,'."\n";
		$html = $html.'		showBody: "##", '."\n";
		$html = $html.'		id: \'cktooltip\''."\n";
		$html = $html.'	});'."\n";
			
		$html = $html.'	// validate signup form on keyup and submit'."\n";
		$html = $html.'	JNC_jQuery("#ckform").validate({'."\n";
		$html = $html.'		//	debug: true,'."\n";
		$html = $html.'			errorElement: "div",'."\n";
		$html = $html.'		//	errorContainer: $("#warning, #summary"),'."\n";
		$html = $html.'		//	errorPlacement: function(error, element) {'."\n";
		$html = $html.'		//	error.appendTo(element.next(".error"));'."\n";
		$html = $html.'		//},'."\n";
		$html = $html.'		rules: {'."\n";
		

		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $fields[$i];
					
			if ($field->mandatory == "1" || ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != ''))) {
							
				if ($field->typefield != "select") $html = $html.$field->name.':{'."\n";
				if ($field->typefield == "select") $html = $html.'"'.$field->name.'[]":{'."\n";
							
				if ($field->mandatory == "1") {
					$html = $html.'required: true'."\n";
					if ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != '')) $html = $html.',';
					$html = $html."\n";
				}
				if ($field->typefield == 'text' && $field->t_minchar != '')
				{
					$html = $html.'minlength: '.$field->t_minchar."\n";
					if ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number') $html = $html.',';
					$html = $html."\n";
				}
				
				if ($field->typefield == 'text' && $field->t_texttype == 'email') {
					$html = $html.'email: true'."\n";
					if ($field->t_texttype == 'date' || $field->t_texttype == 'number') $html = $html.',';
					$html = $html."\n";
				}
				if ($field->typefield == 'text' && $field->t_texttype == 'date') {
					$html = $html.'date: true'."\n";
					if ($field->t_texttype == 'number') $html = $html.',';
					$html = $html."\n";
				}
				if ($field->typefield == 'text' && $field->t_texttype == 'number') {
					$html = $html.'number: true'."\n";
					$html = $html."\n";
				}
				$html = $html.'}'."\n";
				if ($field->name != $lastFieldsToValidate) $html = $html.',';
			}
		}
					
		if ($form->captcha == 1)
		{
			$html = $html.',';
			$html = $html.'ck_captcha_code:{required: true}'."\n";
		}
	
		$html = $html.'}'."\n";
		$html = $html.'});'."\n";
	
		$html = $html.'JNC_jQuery(\'#captcharefresh\').bind(\'click\',function (event) {'."\n";
		$html = $html.'	JNC_jQuery(\'#captchacode\').attr("src","index.php?option=com_ckforms&task=captcha&sid=" + Math.random());'."\n";
		$html = $html.'});'."\n";
			
		$html = $html.'});'."\n";
		
		$html = $html.'</script>'."\n";
	
		$html = $html.'<table class="contentpaneopen'.$pageclass_sfx.'" id="ckformcontainer">'."\n";
		$html = $html.'<tr><td>'."\n";
	
		if (strcmp ( $form->description , "" ) != 0) 
		{ 
			$html = $html.'<p>'.$form->description.'</p>'."\n";
		}
	
		$mandatory = false;
		$upload = false;
		$custominfo = false;
		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $fields[$i];
			if ($field->mandatory == "1") $mandatory = true;
			if ($field->typefield == "fileupload") $upload = true;
			if ($field->custominfo != "") $custominfo = true;
		}

		if ($mandatory == true)
		{
			$html = $html.'<p class="ck_mandatory">'.JText::_( 'Mandatory' ).'*</p>'."\n";
		}
	
		$html = $html.'<form action="'.$this->formLink.'" method="post" id="ckform" class="form-validate ckform"';
		if($upload == true) 
		{
			$html = $html.'enctype="multipart/form-data"';
		}
		$html = $html.'>'."\n";
		$html = $html.'<input name="id" id="id" type="hidden" value="'.$form->id.'" />'."\n";
					
		if ($upload == true) 
		{
			$html = $html.'<input type="hidden" name="MAX_FILE_SIZE" value="'.$form->maxfilesize.'" />'."\n";
		} 
	
		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $fields[$i];
			if ($field->typefield == "hidden")
			{
        		$html = $html.'<input name="<?php echo '.$field->name.'" id="'.$field->name.'" type="hidden" value="'.$field->t_initvalueH.'" />'."\n";
			}
		}

		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $fields[$i];
			if ($field->typefield != "hidden" && $field->typefield != "button")
			{
				$html = $html.'<label class="ckCSSlabel';
				if ($field->custominfo != "" && $field->typefield == "textarea") 
					$html = $html.' ckCSSbot5';
				$html = $html.'" id="'.$field->name.'lbl" for="'.$field->name.'"> '.$field->label."\n";

				if ($field->mandatory == '1') 
				{
					$html = $html.'&nbsp;<span class="ck_mandatory">*</span>'."\n";
				}
				if ($field->custominfo != "" && $field->typefield == "textarea") 
				{
					$html = $html.'<img class="ckform_tooltip" src="'.JURI::root(true).'/components/com_ckforms/img/info.png" title="'.$field->custominfo.'" />'."\n";
				}
				$html = $html.'</label>'."\n";
				
				switch ($field->typefield)
				{
					case 'text':
        		
					switch ($field->t_texttype)
					{
						case 'text':
						$html = $html.'<input type="text" name="'.$field->name.'" id="'.$field->name.'" value="'.$field->t_initvalueT.'" class="inputbox ckrequired ckCSSinputnormal ';
						if ($field->custominfo != "") 
						{
							$html = $html.'ckCSSTip';
						} else {
							$html = $html.'ckCSSnoTip';
						} 
						$html = $html.'" maxlength="'.$field->t_maxchar.'" />'."\n";      		
						break;

						case 'password':
        				$html = $html.'<input type="password" name="'.$field->name.'" id="'.$field->name.'" value="'.$field->t_initvalueT.'" class="inputbox ckrequired ckCSSinputnormal ';
						if ($field->custominfo != "") 
						{
							$html = $html.'ckCSSTip';
						} else {
							$html = $html.'ckCSSnoTip';
						} 
						$html = $html.'" maxlength="'.$field->t_maxchar.'" />'."\n";   
			            break;
            
						case 'number':
        				$html = $html.'<input type="text" name="'.$field->name.'" id="'.$field->name.'" value="'.$field->t_initvalueT.'" class="inputbox ckrequired ckCSSinputnormal ';
						if ($field->custominfo != "") 
						{
							$html = $html.'ckCSSTip';
						} else {
							$html = $html.'ckCSSnoTip';
						}
						$html = $html.'" maxlength="'.$field->t_maxchar.'" />'."\n";
						break;
						
						case 'email':
        				$html = $html.'<input type="text" name="'.$field->name.'" id="'.$field->name.'" value="'.$field->t_initvalueT.'" class="inputbox ckrequired ckCSSinputnormal ';
						if ($field->custominfo != "") 
						{
							$html = $html.'ckCSSTip';
						} else {
							$html = $html.'ckCSSnoTip';
						}
						$html = $html.'" maxlength="'.$field->t_maxchar.'" />'."\n";	
						break;
						
						case 'date':
						$html = $html.'<script type="text/javascript">'."\n";
						$html = $html.'<!--'."\n";
    					$html = $html.'JNC_jQuery(function() {'."\n";
        				$html = $html.'JNC_jQuery("#'.$field->name.'").datepicker(JNC_jQuery.extend({}, JNC_jQuery.datepicker.regional["fr"], {'."\n";
            			$html = $html.'dateFormat: "dd/mm/yy",'."\n";
            			$html = $html.'showOn: "both",'."\n";
						$html = $html.'yearRange: "-100:+5",'."\n";
            			$html = $html.'buttonImage: "'.JURI::root(true).'/components/com_ckforms/img/calendar.gif", '."\n";
            			$html = $html.'buttonImageOnly: true'."\n";
        				$html = $html.'}));'."\n";
    					$html = $html.'});'."\n";
						$html = $html.'// -->'."\n";
						$html = $html.'</script>'."\n";

						$html = $html.'<input type="text" name="'.$field->name.'" id="'.$field->name.'" value="'.$field->t_initvalueT.'" class="inputbox ckrequired ckCSSinputnowidth" maxlength="10" />'."\n";
						break;
					}
					break; 		

					case 'fileupload':
					$html = $html.'<input name="'.$field->name.'" type="file" class="ckCSSinputnormal ';
					if ($field->custominfo != "") 
					{
						$html = $html.'ckCSSTip';
					} else {
						$html = $html.'ckCSSnoTip';
					}
					$html = $html.' />'."\n";
					break; 	

					case 'textarea':
					if ($field->t_HTMLEditor == 1) 
					{	
						$html = $html.'<div class="ckCSSclear ckCSSbot10">'."\n";
						$editorDesc = JFactory::getEditor();
						$editor_param['smilies'] = '0';
						$editor_param['layer'] = '0';
						$html = $html.$editorDesc->display($field->name, $field->t_initvalueTA, '97%', 200, $field->t_columns, $field->t_rows,true,$editor_param);
						$html = $html.'</div>'."\n";
					} else {
        				$html = $html.'<textarea class="ckCSSinputnormal ckCSSnoTip" name="'.$field->name.'" id="'.$field->name.'" cols="'.$field->t_columns.'" rows="'.$field->t_rows.'" wrap="'.$field->t_wrap.'">'.$field->t_initvalueTA.'</textarea>'."\n";
		            }
					break; 	
			
					case 'checkbox':
					$html = $html.'<input class="ckCSStop10" name="'.$field->name.'" id="'.$field->name.'" type="checkbox" value="'.$field->t_initvalueCB.'" ';
					if ($field->t_checkedCB == '1') 
					{
						$html = $html.' checked';
					}
					$html = $html.' />'."\n";
					break; 	

					case 'radiobutton':
					$opt = explode("[-]", $field->t_listHRB);
					$k=count($opt);
					for ($j=0; $j < $k; $j++)
					{	
						$checked = "";
						$val = explode("==", $opt[$j]);
						$key = explode("||", $val[1]);
						$ipos = strpos ($key[1],' [default]');
						if ($ipos != false) 
						{
							$checked = "checked";
							$key[1] = substr($key[1],0,$ipos);
						}
					$html = $html.'<input class="ckCSStop10" name="'.$field->name.'" id="'.$field->name.'" type="radio" value="'.$key[0].'" '.$checked.' />'."\n";
					$html = $html.'&nbsp;'.$key[1].'&nbsp;'."\n";
					} 
					break;
					
					case 'select':
					$html = $html.'<select class="ckCSSinputnowidth" name="'.$field->name.'[]" id="'.$field->name.'" size="'.$field->t_heightS.'" ';
					if ($field->t_multipleS == '1') 
					{
						$html = $html.' multiple';
					}
					$html = $html.' >'."\n";
					if (($field->t_multipleS != '1' && ($field->t_heightS == '' || $field->t_heightS <= 1)) && strpos($field->t_listHS,' [default]') == false) 
					{
						$html = $html.'<option value="">'.strpos($field->t_listHS,' [default]').'</option>'."\n";
					}  		
					
					$opt = explode("[-]", $field->t_listHS);
					$k=count($opt);
					for ($j=0;$j < $k; $j++)
					{	
						$checked = "";
						$val = explode("==", $opt[$j]);
						$key = explode("||", $val[1]);
						$ipos = strpos ($key[1],' [default]');
						if ($ipos != false) {
							$checked = 'selected="selected"';
							$key[1] = substr($key[1],0,$ipos);
						}
						$html = $html.'<option value="'.$key[0].'" '.$checked.' >'.$key[1].'&nbsp;</option>'."\n";
					}
					$html = $html.'</select>'."\n";
					break;
				}	

				if ($field->custominfo != "" && $field->typefield != "textarea") 
				{
					$html = $html.'<img class="ckform_tooltip" src="'.JURI::root(true).'/components/com_ckforms/img/info.png" title="'.$field->custominfo.'" />'."\n";
				}

				if ($field->mandatory == "1" || ($field->typefield == 'text' 
					&& ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != '')))
				{
					$html = $html.'<div class="error" htmlfor="'.$field->name.'">'."\n";
					$html = $html.'<div class="ckCSSFloatLeft">&nbsp;</div>'."\n";
					$html = $html.'<div>'."\n";
					if ($field->customerror == "") 
					{
						$html = $html.JText::_( 'Field not valid (required or bad value)' );
					} else {
						$html = $html.$field->customerror;
					}
					$html = $html.'</div>'."\n";
					$html = $html.'<div class="ckCSSclear"></div>'."\n";
					$html = $html.'</div>'."\n";
				}
   				$html = $html.'<p class="ckCSSclear" />'."\n"; 
			}   
		}

		if ($form->captcha == 1)
		{
			$html = $html.'<br/>'."\n"; 
			$html = $html.'<div class="ckCSStop10">'."\n"; 
			$html = $html.'<img id="captchacode" src="index.php?option=com_ckforms&task=captcha&sid=c4ce9d9bffcf8ba3357da92fd49c2457" align="absmiddle"> &nbsp;'."\n"; 
			$html = $html.'<img alt="Refresh Captcha" id="captcharefresh" src="'.JURI::root(true).'/components/com_ckforms/captcha/images/refresh.gif" align="absmiddle"> &nbsp;'."\n"; 
			$html = $html.'<input type="text" id="ck_captcha_code" name="ck_captcha_code" /><br />'."\n"; 
			$html = $html.'<div class="error" htmlfor="ck_captcha_code" class="error">'."\n"; 
			$html = $html.JText::_( 'Field not valid (required or bad value)' )."\n"; 
			$html = $html.'</div>'."\n"; 
			$html = $html.'</div>'."\n"; 
		}

	    $html = $html.'<div class="ckCSScenter">'."\n";

		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $fields[$i];
			if ($field->typefield == "button")
			{
				$jsbutton = "";
				if ($field->t_typeBT == "submit") 
				{
    				$html = $html.'<input name="submit_bt" id="submit_bt" type="submit" value="'.$field->label.'" '.$jsbutton.' />'."\n";
   					$html = $html.'&nbsp;'."\n";
				} 
				else if ($field->t_typeBT == "reset") 
				{
    				$html = $html.'<input name="reset_bt" id="reset_bt" type="reset" value="'.$field->label.'" />&nbsp;'."\n";
				}
			}
		}
		$html = $html.'</div>'."\n";
    
		$html = $html.'</form>'."\n";

		if ($form->poweredby == '1') 
		{
			$html = $html.'<div id="ckpoweredby"><a href="http://joomlacode.org/gf/project/ckforms/" target="_blank">'."\n";
			$html = $html.JText::_( 'Powered by CK Forms' ).'</a></div>'."\n";
		} 
		
		$html = $html.'</td></tr>'."\n";
		$html = $html.'</table>'."\n";

		return $html;
	}

}
