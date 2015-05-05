<?php defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">

	function submitbutton(pressbutton)	{
		var form = document.adminForm;
	
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
	
		// do field validation
		if (form.name.value == "") {
			alert( "<?php echo JText::_( 'Field must have a name', true ); ?>" );
		} else if (form.name.value.match(/[a-zA-Z0-9]*/) != form.name.value) {
			alert( "<?php echo JText::_( 'Field name contains bad caracters', true ); ?>" );
		} else if (form.typefield.options[form.typefield.selectedIndex].value == "0") {
			alert( "<?php echo JText::_( 'You must select a field type', true ); ?>" );		
		} else {
			submitform( pressbutton );
		}
	}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<input type="hidden" id="t_listHS" name="t_listHS" value="<?php echo $this->ckfield->t_listHS;?>" />
<input type="hidden" id="t_listHRB" name="t_listHRB" value="<?php echo $this->ckfield->t_listHRB;?>" />

<div class="col100">
	<fieldset class="adminform">
    <div>
	<table border="0" cellpadding="0" cellspacing="0">
    <tr><td valign="top" width="100%">
		<table border="0" cellpadding="2" cellspacing="2" class="admintable" >
		<tr>
			<td width="100px" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="normalField" type="text" name="name" id="name" maxlength="50" value="<?php echo $this->ckfield->name;?>" />
			</td>
            
		</tr>
		<tr>
			<td width="100px" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Label' ); ?>:
				</label>
			</td>
			<td>
				<input class="normalField" type="text" name="label" id="label" maxlength="250" value="<?php echo $this->ckfield->label;?>" />
			</td>
		</tr>        
        <tr>
			<td width="100px" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Published' ); ?>:
				</label>
			</td>
            <td>
            <?php
                echo JHTML::_('select.booleanlist',  'published', '', $this->ckfield->published);  
            ?>
            </td>        
        </tr>
        
        <tr>
			<td width="100px" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Type' ); ?>:
				</label>
			</td>
            <td>
            <?php
                echo $this->listtypes;
            ?>
            </td>        
        </tr>
		</table>
		
        <table border="0" cellpadding="1" cellspacing="1" class="admintable" >    
		<tr id="mandatory_row">
			<td width="100px" align="right" class="key">
				<label for="mandatory">
					<?php echo JText::_( 'Mandatory' ); ?>:
				</label>
			</td>
			<td>
				<input name="mandatory" id="mandatory" type="checkbox" value="1" <?php if ($this->ckfield->mandatory == '1') { ?> checked <?php } ?>/>
			</td>
		</tr> 

		<tr id="custominfo_row">
			<td width="100px" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Tips text' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="custominfo" id="custominfo" maxlength="500" value="<?php echo $this->ckfield->custominfo;?>" />
			</td>
		</tr>        

		<tr id="customerror_row">
			<td width="100px" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Custom error text' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="customerror" id="customerror" maxlength="500" value="<?php echo $this->ckfield->customerror;?>" />
			</td>
		</tr>        
        
	</table>
        
    <table class="admintable" id="ckf_length" border="0">
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Max length' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_maxchar" type="text" id="t_maxchar" value="<?php if (Isset($this->ckfield->t_maxchar)) echo $this->ckfield->t_maxchar;?>" maxlength="5" />
			</td>
		</tr>
 		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Min length' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_minchar" type="text" id="t_minchar" value="<?php if (Isset($this->ckfield->t_minchar)) echo $this->ckfield->t_minchar;?>" maxlength="5" />
			</td>
		</tr>
	</table>
    
    <?php // FORM - TEXT ?>
    <table class="admintable" id="ckf_text" border="0">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Initial value' ); ?>:
				</label>
			</td>
			<td>
				<input  class="field300" type="text" name="t_initvalueT" id="t_initvalueT" value="<?php if (Isset($this->ckfield->t_initvalueT)) echo $this->ckfield->t_initvalueT;?>" />
			</td>
		</tr>
        
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Text type' ); ?>:
				</label>
			</td>
            <td>
            <?php
                echo $this->texttype;
            ?>
            </td>        
        </tr> 
                        
    </table>
    
    <?php // FORM - HIDDEN ?>
    <table class="admintable" id="ckf_hidden" border="0">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Value' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="t_initvalueH" id="t_initvalueH" value="<?php if (Isset($this->ckfield->t_initvalueH)) echo $this->ckfield->t_initvalueH;?>" />
			</td>
		</tr>                   
    </table>
        
    <?php // FORM - TEXTAREA ?>
    <table class="admintable" id="ckf_textarea">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Initial value' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="t_initvalueTA" id="t_initvalueTA" value="<?php if (Isset($this->ckfield->t_initvalueTA)) echo $this->ckfield->t_initvalueTA;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="mandatory">
					<?php echo JText::_( 'HTML Editor' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_HTMLEditor" id="t_HTMLEditor" type="checkbox" value="1" <?php if (Isset($this->ckfield->t_HTMLEditor)) { if ($this->ckfield->t_HTMLEditor == '1') { ?> checked <?php }} ?>/>
			</td>
		</tr>
        
                 
 		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Columns' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_columns" type="text" id="t_columns" value="<?php if (Isset($this->ckfield->t_columns)) echo $this->ckfield->t_columns;?>" maxlength="5" />
			</td>
		</tr>
 		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Rows' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_rows" type="text" id="t_rows" value="<?php if (Isset($this->ckfield->t_rows)) echo $this->ckfield->t_rows;?>"  maxlength="5" />
			</td>
		</tr>
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Wrap' ); ?>:
				</label>
			</td>
            <td>
            <?php
                echo $this->listwrap;
            ?>
            </td>        
        </tr>                                             
    </table>
            
    <?php // FORM - CHECKBOX ?>
    <table class="admintable" id="ckf_checkbox">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Value' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="t_initvalueCB" id="t_initvalueCB" value="<?php if (Isset($this->ckfield->t_initvalueCB)) echo $this->ckfield->t_initvalueCB;?>" />
			</td>
		</tr>                   
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Checked' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_checkedCB" id="t_checkedCB" type="checkbox" value="1" <?php if (Isset($this->ckfield->t_checkedCB)) { if ($this->ckfield->t_checkedCB == '1') { ?> checked <?php }} ?>/>
			</td>
		</tr>  
    </table>
    
    <?php // FORM - RADIOBUTTON ?>
    <table class="admintable" id="ckf_radiobutton" border="0">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Value' ); ?>:
				</label>
			</td>
			<td width="360px">
				<input class="field300" type="text" name="t_valueRB" id="t_valueRB" value="" />
			</td>
			<td width="75" align="right">
				<label for="title">
					<?php echo JText::_( 'Default' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_defaultRB" id="t_defaultRB" type="checkbox" value="1" />
			</td>
		</tr> 
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Label' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="t_labelRB" id="t_labelRB" value="" />
			</td>
			<td colspan="2">
            	<input name="add" onclick="addValueToList('t_listRB','t_listHRB','t_valueRB','t_labelRB','t_defaultRB');" type="button" value="add" />
                &nbsp;<input onclick="resetValues('t_valueRB','t_labelRB','t_defaultRB');" name="reset" type="button" value="reset" />
			</td>
		</tr>  
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Checkbox list' ); ?>:
				</label>
			</td>
			<td>
            	<select class="field300" id="t_listRB" name="t_listRB" size="3" multiple onchange="editValueList('t_listRB','t_valueRB','t_labelRB','t_defaultRB')">
  				</select>
			</td>
			<td colspan="2">
                <input onclick="removeOptions('t_listRB','t_listHRB');" name="del" type="button" value="del" />
			</td>
		</tr>                   
    </table>
                
    <?php // FORM - SELECT ?>
    <table class="admintable" id="ckf_select" border="0">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Allow multiple selection' ); ?>:
				</label>
			</td>
			<td colspan="3">
				<input name="t_multipleS" id="t_multipleS" type="checkbox" value="1" <?php if (Isset($this->ckfield->t_multipleS)) { if ($this->ckfield->t_multipleS == '1') { ?> checked <?php }} ?>/>
			</td>
		</tr>  
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Height' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="t_heightS" id="t_heightS" value="<?php if (Isset($this->ckfield->t_heightS)) echo $this->ckfield->t_heightS; ?>" />
			</td>
            <td colspan="2">
            </td>
		</tr>                   
    
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Value' ); ?>:
				</label>
			</td>
			<td width="360px">
				<input class="field300" type="text" name="t_valueS" id="t_valueS" value="" />
			</td>
			<td align="right"  width="75">
				<label for="title">
					<?php echo JText::_( 'Selected' ); ?>:
				</label>
			</td>
			<td>
				<input name="t_defaultS" id="t_defaultS" type="checkbox" value="1" />
			</td>
		</tr> 
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Label' ); ?>:
				</label>
			</td>
			<td>
				<input class="field300" type="text" name="t_labelS" id="t_labelS" value="" />
			</td>
			<td colspan="2">
            	<input name="add" onclick="addValueToList('t_listS','t_listHS','t_valueS','t_labelS','t_defaultS');" type="button" value="add" />
                &nbsp;<input onclick="resetValues('t_valueS','t_labelS','t_defaultS');" name="reset" type="button" value="reset" />
			</td>
            
		</tr>           
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Checkbox list' ); ?>:
				</label>
			</td>
			<td>
            	<select class="field300" id="t_listS" name="t_listS" size="3" multiple onchange="editValueList('t_listS','t_valueS','t_labelS','t_defaultS')">
  				</select>
			</td>
			<td colspan="2">
            	<input onclick="removeOptions('t_listS','t_listHS');" name="del" type="button" value="del" />
			</td>
		</tr>                   
    </table>
                
    <?php // FORM - Button ?>
    <table class="admintable" id="ckf_button">
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Type' ); ?>:
				</label>
			</td>
            <td>
            <?php
                echo $this->listbuttontype;
            ?>
            </td>        
        </tr>                                             
    </table>
    </td>
    
	<?php // Display Help & Tips ?>
        <td valign="top">
            <div class="ckhelptd">
                <ul>
                    <li><?php echo JText::_( '<b>Field name</b><br/> The field name can only contain the following characters : <br/><b>abcdefghijklmnopqrstuvwxy<br/>ABCDEFGHIJKLMNOPQRSTUVWXYZ<br/>0123456789</b>' ); ?></li>
                    <li><?php echo JText::_( 'Add caracters "##" to separate the title and the body of tips text<br/>start with caracters "##" to not have a title' ); ?></li>
                </ul>
            </div>
        </td>
    <?php ?>            
    
    </tr>
    </table>
                
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_ckforms" />
<input type="hidden" name="id" value="<?php echo $this->ckfield->id; ?>" />
<input type="hidden" name="fid" value="<?php echo $this->fid; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->ckfield->ordering; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="ckfields" />

</form>
