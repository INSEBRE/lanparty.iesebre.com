<?php // no direct access

	defined('_JEXEC') or die('Restricted access'); 

	$nbFields=count($this->ckforms->fields );

?>

<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $this->ckforms->title; ?></div>

<?php 	
	$lastFieldsToValidate = "";
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->mandatory == "1" || ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != ''))) {
			$lastFieldsToValidate = $field->name;
		}
	}
?>		

<script type="text/javascript">

var JNC_jQuery = jQuery.noConflict();
JNC_jQuery(function() {

	JNC_jQuery(".ckform_tooltip").tooltip({
		track: true,
		showURL: false,
		fade: 250,
		showBody: "##", 
		id: 'cktooltip'
	});
	
	// validate signup form on keyup and submit
	JNC_jQuery("#ckform").validate({
		//	debug: true,
			errorElement: "div",
		//	errorContainer: $("#warning, #summary"),
		//	errorPlacement: function(error, element) {
		//	error.appendTo(element.next(".error"));
		//},
		rules: {
<?php 		
		for ($i=0;$i < $nbFields; $i++)
		{ 
			$field = $this->ckforms->fields[$i];
			
			if ($field->mandatory == "1" || ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != ''))) {
					
				if ($field->typefield != "select") echo $field->name.":{";
				if ($field->typefield == "select") echo '"'.$field->name.'[]":{';
				
				if ($field->mandatory == "1") {
					echo "required: true";
					if ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number' || $field->t_minchar != '')) echo ',';
					echo "\n";
				}
				if ($field->typefield == 'text' && $field->t_minchar != '')
				{
					echo "minlength: ".$field->t_minchar;
					if ($field->t_texttype == 'email' || $field->t_texttype == 'date' || $field->t_texttype == 'number') echo ',';
					echo "\n";
				}
				if ($field->typefield == 'text' && $field->t_texttype == 'email') {
					echo "email: true";
					if ($field->t_texttype == 'date' || $field->t_texttype == 'number') echo ',';
					echo "\n";
				}
				if ($field->typefield == 'text' && $field->t_texttype == 'date') {
					echo "date: true";
					if ($field->t_texttype == 'number') echo ',';
					echo "\n";
				}
				if ($field->typefield == 'text' && $field->t_texttype == 'number') {
					echo "number: true";
					echo "\n";
				}
				echo "}";
				if ($field->name != $lastFieldsToValidate) echo ',';
			}
		}
		
		if ($this->ckforms->captcha == 1)
		{
			echo ',';
			echo "ck_captcha_code:{required: true}";
		}
?>		
		}
	});

	JNC_jQuery('#captcharefresh').bind('click',function (event) {
		JNC_jQuery('#captchacode').attr("src","index.php?option=com_ckforms&task=captcha&sid=" + Math.random());
	});

});

</script>

<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>" id="ckformcontainer">
<tr><td>
  <?php if (strcmp ( $this->ckforms->description , "" ) != 0) { ?>
	<p><?php echo $this->ckforms->description; ?></p>
  <?php } ?>

<?php 
	$mandatory = false;
	$upload = false;
	$custominfo = false;
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->mandatory == "1") $mandatory = true;
		if ($field->typefield == "fileupload") $upload = true;
		if ($field->custominfo != "") $custominfo = true;
	}
	
	if ($mandatory == true)
	{
?>
	<p class="ck_mandatory"><?php echo JText::_( 'Mandatory' ); ?> *</p>
<?php } ?>

	<form action="<?php echo $this->formLink; ?>" method="post" id="ckform" class="form-validate ckform"<?php if($upload == true) { ?> enctype="multipart/form-data"<?php } ?>>
        <input name="id" id="id" type="hidden" value="<?php echo $this->ckforms->id; ?>" />
                
<?php if($upload == true) { ?>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $this->ckforms->maxfilesize; ?>"" />
<?php } 
 
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield == "hidden")
		{
?>
        <input name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" type="hidden" value="<?php echo $field->t_initvalueH; ?>" />
<?php    
		}
	}

	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield != "hidden" && $field->typefield != "button")
		{
	
?>       
		<label class="ckCSSlabel<?php if ($field->custominfo != "" && $field->typefield == "textarea") echo " ckCSSbot5"; ?>" id="<?php echo $field->name."lbl"; ?>" for="<?php echo $field->name; ?>"> <?php echo $field->label; ?>
<?php 
	if ($field->mandatory == '1') 
	{ ?>
			&nbsp;<span class="ck_mandatory">*</span>
<?php 
	}
	if ($field->custominfo != "" && $field->typefield == "textarea") 
    {
?>       
			<img class="ckform_tooltip" src="<?php echo JURI::root(true).'/components/com_ckforms/'; ?>img/info.png" title="<?php echo $field->custominfo; ?>" />
<?php
	}
?>       
        </label>
<?php
	switch ($field->typefield)
	{
		case 'text':
?>
<?php        		
		switch ($field->t_texttype)
		{
			case 'text':
?>
		<input type="text" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php echo $field->t_initvalueT; ?>" class="inputbox ckrequired ckCSSinputnormal <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?>" maxlength="<?php echo $field->t_maxchar; ?>" />
<?php        		
            break;
            case 'password':
?>		
        <input type="password" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php echo $field->t_initvalueT; ?>" class="inputbox ckrequired ckCSSinputnormal <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?>" maxlength="<?php echo $field->t_maxchar; ?>" />
<?php
            break;
            case 'number':
?>
        <input type="text" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php echo $field->t_initvalueT; ?>" class="inputbox ckrequired ckCSSinputnormal <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?>" maxlength="<?php echo $field->t_maxchar; ?>" />
<?php
			break;
			case 'email':
?>
        <input type="text" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php echo $field->t_initvalueT; ?>" class="inputbox ckrequired ckCSSinputnormal <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?>" maxlength="<?php echo $field->t_maxchar; ?>" />
<?php	
			break;
			case 'date':
?>
<script type="text/javascript">
<!--
    JNC_jQuery(function() {
        JNC_jQuery("#<?php echo $field->name; ?>").datepicker(JNC_jQuery.extend({}, JNC_jQuery.datepicker.regional["fr"], { 
            dateFormat: "dd/mm/yy",
            showOn: "both", 
            buttonImage: "<?php echo JURI::root(true).'/components/com_ckforms/' ?>img/calendar.gif", 
            buttonImageOnly: true,
            yearRange: '-100:+5'
        }));
    });
// -->
</script>

		<input type="text" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php echo $field->t_initvalueT; ?>" class="inputbox ckrequired ckCSSinputnowidth" maxlength="10" />
<?php	
			break;
		}
		break; 		

		case 'fileupload':
?>
		<input name="<?php echo $field->name; ?>" type="file" class="ckCSSinputnormal <?php if ($field->custominfo != "") {echo "ckCSSTip";} else {echo "ckCSSnoTip";} ?>" />
<?php
		break; 	
	
		case 'textarea':
			if ($field->t_HTMLEditor == 1) 
			{	
?>
        <div class="ckCSSclear ckCSSbot10">    
<?php
				$editorDesc = JFactory::getEditor();
				$editor_param['smilies'] = '0';
				$editor_param['layer'] = '0';
				echo $editorDesc->display($field->name, $field->t_initvalueTA, '97%', 200, $field->t_columns, $field->t_rows,true,$editor_param);
?>
        </div>    
<?php
			} else {
?>
        <textarea class="ckCSSinputnormal ckCSSnoTip" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" cols="<?php echo $field->t_columns; ?>" rows="<?php echo $field->t_rows; ?>" wrap="<?php echo $field->t_wrap; ?>"><?php echo $field->t_initvalueTA; ?></textarea>
<?php
            }
		break; 	
			
		case 'checkbox':
?>
		<input class="ckCSStop10" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" type="checkbox" value="<?php echo $field->t_initvalueCB; ?>" <?php if ($field->t_checkedCB == '1') { ?> checked<?php } ?> />
<?php
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
				if ($ipos != false) {
					$checked = "checked";
					$key[1] = substr($key[1],0,$ipos);
				}
?>
			<input class="ckCSStop10" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" type="radio" value="<?php echo $key[0]; ?>" <?php echo $checked; ?> />
			&nbsp;<?php echo $key[1]; ?>&nbsp;
<?php 				
			} 
			 	
		break;
			
		case 'select':
		?>
			<select class="ckCSSinputnowidth" name="<?php echo $field->name; ?>[]" id="<?php echo $field->name; ?>" size="<?php echo $field->t_heightS; ?>" <?php if ($field->t_multipleS == '1') { ?> multiple<?php } ?> >
		<?php if (($field->t_multipleS != '1' && ($field->t_heightS == '' || $field->t_heightS <= 1)) && strpos($field->t_listHS,' [default]') == false) { ?>
			<option value=""><?php echo strpos($field->t_listHS,' [default]'); ?></option>
		<?php }  		
			
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
		?>
			<option value="<?php echo $key[0]; ?>" <?php echo $checked; ?> ><?php echo $key[1]; ?>&nbsp;</option>
		<?php 				
			}
		?>
			</select>
		<?php 
		break;
	}	
	
	if ($field->custominfo != "" && $field->typefield != "textarea") 
    {
?>
		<img class="ckform_tooltip" src="<?php echo JURI::root(true).'/components/com_ckforms/'; ?>img/info.png" title="<?php echo $field->custominfo; ?>" />
<?php
	}

if ($field->mandatory == "1" || ($field->typefield == 'text' && ($field->t_texttype == 'email' || $field->t_texttype == 'date' || 		$field->t_texttype == 'number' || $field->t_minchar != ''))) {
 ?>
 	<div class="error" htmlfor="<?php echo $field->name; ?>">
        <div class="ckCSSFloatLeft">&nbsp;</div> 
        <div>
        <?php	if ($field->customerror == "") 
                {
                    echo JText::_( 'Field not valid (required or bad value)' );
                } else {
                    echo $field->customerror;
                }
         ?>
        </div>
        <div class="ckCSSclear"></div>
    </div>
<?php
	}
?>
    <p class="ckCSSclear" />
            
<?php  
	}   
}
?>

<?php 
	if ($this->ckforms->captcha == 1)
	{
?>
	<br/>
	<div class="ckCSStop10">
        <img id="captchacode" src="index.php?option=com_ckforms&task=captcha&sid=c4ce9d9bffcf8ba3357da92fd49c2457" align="absmiddle"> &nbsp;           
        <img alt="Refresh Captcha" id="captcharefresh" src='<?php echo JURI::root(true).'/components/com_ckforms/'; ?>captcha/images/refresh.gif' align="absmiddle"> &nbsp;
        <input type="text" id="ck_captcha_code" name="ck_captcha_code" /><br />
        <div class="error" htmlfor="ck_captcha_code" class="error">
        	<?php echo JText::_( 'Field not valid (required or bad value)' ); ?>
        </div>    
    </div>
<?php
	}
?>
    
    <div class="ckCSScenter">
	<?php 
	for ($i=0;$i < $nbFields; $i++)
	{ 
		$field = $this->ckforms->fields[$i];
		if ($field->typefield == "button")
		{
			$jsbutton = "";
			if ($field->t_typeBT == "submit") {
	?>
    			<input name="submit_bt" id="submit_bt" type="submit" value="<?php echo $field->label; ?>" <?php echo $jsbutton; ?> />
   			&nbsp;
<?php 		
		} else if ($field->t_typeBT == "reset") {
		
	?>
    		<input name="reset_bt" id="reset_bt" type="reset" value="<?php echo $field->label; ?>" />&nbsp;
    <?php 
		}?>
    <?php    
	}
}
?>
	</div>
    
</form>

<?php if ($this->ckforms->poweredby == '1') { ?>
	<div id="ckpoweredby" colspan="<?php if ($custominfo == true){?>3<?php } else {?>2<?php }?>"><a href="http://joomlacode.org/gf/project/ckforms/" target="_blank"><?php echo JText::_( 'Powered by CK Forms' ); ?></a></div>
<?php } 
?>

</td></tr>
</table>
