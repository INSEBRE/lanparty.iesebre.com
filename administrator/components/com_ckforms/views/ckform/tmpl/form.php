<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	jimport( 'joomla.html.editor' ); 
?>

<script type="text/javascript">

var JNC_jQuery = jQuery.noConflict();      
JNC_jQuery(function() {	
	JNC_jQuery('#tabcontainer > ul').tabs();	
});
    
function submitbutton(pressbutton)	{
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.name.value == ""){
		alert( "<?php echo JText::_( 'Form must have a name', true ); ?>" );
	} else if (form.name.value.match(/[a-zA-Z0-9]*/) != form.name.value) {
		alert( "<?php echo JText::_( 'Field name contains bad caracters', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}

</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
 
    <div id="tabcontainer">
    
        <ul class="ui-tabs-nav">
        	<li class="ui-tabs-selected"><a href="#fragment-1"><span><?php echo JText::_( 'General' ); ?></span></a></li>
          	<li class="ui-tabs-unselect"><a href="#fragment-2"><span><?php echo JText::_( 'Result' ); ?></span></a></li>
          	<li class="ui-tabs-unselect"><a href="#fragment-3"><span><?php echo JText::_( 'Email' ); ?></span></a></li>
          	<li class="ui-tabs-unselect"><a href="#fragment-4"><span><?php echo JText::_( 'Advanced' ); ?></span></a></li>
      	</ul>

		<div id="fragment-1" class="ui-tabs-container"> 
		<table class="admintable"> 
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="name" id="name" size="50" maxlength="50" value="<?php echo $this->ckforms->name;?>" />
			</td>
            
		<?php // Display Help & Tips ?>
        	<td rowspan="5" valign="top">
            	<div class="ckhelptd">
                	<ul>
                        <li><?php echo JText::_( '<b>Form name</b><br/> The form name can only contain the following characters : <br/><b>abcdefghijklmnopqrstuvwxy<br/>ABCDEFGHIJKLMNOPQRSTUVWXYZ<br/>0123456789</b>' ); ?></li>
                    </ul>
              	</div>
            </td>
        <?php ?>
            
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="title" id="title" size="50" maxlength="250" value="<?php echo $this->ckforms->title;?>" />
			</td>
		</tr>        
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Published' ); ?>:
				</label>
			</td>
        <td>
        <?php 
			echo JHTML::_('select.booleanlist',  'published', '', $this->ckforms->published); 
		?>
        </td>        
        </tr>
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Use Captcha' ); ?>:
				</label>
			</td>
        <td>
        <?php 
			echo JHTML::_('select.booleanlist',  'captcha', '', $this->ckforms->captcha);  
		?>
        </td>        
        </tr>

        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
        <td>
        <?php 	
		
			$editorDesc = JFactory::getEditor();
			echo $editorDesc->display('description',$this->ckforms->description, 600, 200, 10, 10);  
			
		?>
        
        </td>
        </tr>
		</table>
        </div>
        
		<div id="fragment-2" class="ui-tabs-container ui-tabs-hide">
		<table class="admintable" border="0"> 
        <tr>
			<td width="100" align="right" class="key">

				<label for="title">
					<?php echo JText::_( 'Save result' ); ?>:
				</label>
			</td>
        <td>
        
        <?php 
			echo JHTML::_('select.booleanlist',  'saveresult', '', $this->ckforms->saveresult);  
		?>
        </td>    
		<?php // Display Help & Tips ?>
        	<td rowspan="3" valign="top">
            	<div class="ckhelptd">
                	<ul>
                        <li><?php echo JText::_( '<b>Save result "YES"</b> save the data of your form in the Database and can be viewed and exported' ); ?></li>
                        <li><?php echo JText::_( '<b>Text Result</b> is the text displayed after the form submission' ); ?></li>
                        <li><?php echo JText::_( '<b>Redirect URL</b> is the URL displayed after the form submission' ); ?></li>
                    </ul>
              	</div>
            </td>
        <?php ?>            
        </tr>
        
        <tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Text result' ); ?>:
				</label>
			</td>
        <td>

        <?php 
			$editorResult = JFactory::getEditor();
			echo $editorResult->display('textresult',$this->ckforms->textresult, 600, 200, 10, 10);  
		?>
        </td>
        </tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?php echo JText::_( 'Redirect URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="redirecturl" id="redirecturl" maxlength="250" value="<?php echo $this->ckforms->redirecturl;?>" />
			</td>
		</tr>        
		</table>        
        </div>
        
        <div id="fragment-3" class="ui-tabs-container ui-tabs-hide">
        <table class="admintable x-hide-display" id="cktb3">
        <tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Email result' ); ?>:
				</label>
			</td>
        	<td class="cktdfield">
			<?php 
                echo JHTML::_('select.booleanlist',  'emailresult', '', $this->ckforms->emailresult);  
            ?>
            </td>
        
		<?php // Display Help & Tips ?>
        	<td rowspan="6" valign="top">
            	<div class="ckhelptd">
                	<ul>
						<li><?php echo JText::_( 'Separate emails with comma' ); ?></li>
                        <li><?php echo JText::_( '<b>Email result</b> "Yes" send the data submited by email to the emails addresses with the subject.' ); ?></li>
                    </ul>
              	</div>
            </td>
        <?php ?>
                
        </tr>
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail FROM' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="emailfrom" id="emailfrom" maxlength="250" value="<?php echo $this->ckforms->emailfrom;?>" />
			</td>
		</tr> 
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail TO' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="emailto" id="emailto" maxlength="250" value="<?php echo $this->ckforms->emailto;?>" />
			</td>
		</tr> 
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail CC' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="emailcc" id="emailcc" maxlength="250" value="<?php echo $this->ckforms->emailcc;?>" />
			</td>
		</tr> 
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail BCC' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="emailbcc" id="emailbcc" maxlength="250" value="<?php echo $this->ckforms->emailbcc;?>" />
			</td>
		</tr> 
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Mail Subject' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="subject" id="subject" maxlength="250" value="<?php echo $this->ckforms->subject;?>" />
			</td>
		</tr>                       
	</table>
    </div>
    
    <div id="fragment-4" class="ui-tabs-container ui-tabs-hide">
	<table class="admintable x-hide-display" id="cktb4"> 
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Uploaded files path' ); ?>:
				</label>
			</td>
			<td class="cktdfield">
				<input class="text_area" type="text" name="uploadpath" id="uploadpath" maxlength="255" value="<?php echo $this->ckforms->uploadpath;?>" /><br/>
                <?php 
				if ($this->ckforms->uploadpath != null && trim($this->ckforms->uploadpath) != "") 
                {
					if (file_exists ($this->ckforms->uploadpath))					
					{
						echo "<span class=\"txtgreen\">(".JText::_( 'Directory exists' )."</span> - ";
						
						if (is_writable ($this->ckforms->uploadpath))
						{
							echo "<span class=\"txtgreen\">".JText::_( 'directory writables' ).")</span>";
						} else {
							echo "<span class=\"txtred\">".JText::_( 'ERROR : directory read only' )." !".")</span>";
						}
					} else {
						echo "<span class=\"txtred\">(".JText::_( 'ERROR : directory doesnt exist' )." !".")</span>";
					}
                }
				?>
			</td>
			<?php // Display Help & Tips ?>
                <td rowspan="3" valign="top">
                    <div class="ckhelptd noborder">
                    </div>
                </td>
            <?php ?>
            
		</tr>
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'File uploaded maximum size' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="maxfilesize" id="maxfilesize" size="32" maxlength="32" value="<?php echo $this->ckforms->maxfilesize;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key" nowrap="nowrap">
				<label for="title">
					<?php echo JText::_( 'Display "powered by" text' ); ?>:
				</label>
			</td>
			<td>
			<?php 
                echo JHTML::_('select.booleanlist',  'poweredby', '', $this->ckforms->poweredby);  
            ?>

			</td>
		</tr>
        </table>
    </div>
        
</div>

<input type="hidden" name="option" value="com_ckforms" />
<input type="hidden" name="id" value="<?php echo $this->ckforms->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="ckforms" />

</form>

