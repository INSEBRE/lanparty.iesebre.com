<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
 
    <div id="tabs1">
		<table class="admintable">

        <tr>
            <td width="100" align="right" class="key">
                <label for="title">
                   <?php echo JText::_( 'ID' ); ?>
                </label>
            </td>
            <td>
                <?php echo $this->item->id; ?>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="title">
                    <?php echo JText::_( 'Date' ); ?>:
                </label>
            </td>
            <td>
                <?php echo $this->item->created; ?>
            </td>
        </tr>
         
	<?php	$k = 0;
		$n=count( $this->fields );
		for ($i=0; $i < $n; $i++)
		{
			$rowField = $this->fields[$i];
			if ($rowField->typefield != 'button')
			{
				$prop=$rowField->name;
				$texte = $this->item->$prop;
 	?>
    
        <tr>
            <td width="100" align="right" class="key">
                <label for="title">
                    <?php echo  $rowField->name; ?>:
                </label>
            </td>
            <td>
                <?php echo  $texte; ?>
            </td>
        </tr>
            
	<?php	
			}
		}
 	?>

		</table>        
	</div>

<input type="hidden" name="option" value="com_ckforms" />
<input type="hidden" name="id" value="<?php echo $this->ckforms->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="ckforms" />

</form>
