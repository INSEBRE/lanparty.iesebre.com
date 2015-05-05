<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
<table class="adminlist">
<thead>
    <tr>
            <th width="3%">
                <?php echo JText::_( 'Num' ); ?>
            </th>
			<th width="3%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>	
			<?php	$k = 0;
	$n=count( $this->fields );
	for ($i=0; $i < $n; $i++)
	{
		$rowField = $this->fields[$i];
		if ($rowField->typefield != 'button')
		{
 ?>
<th><?php echo  $rowField->name; ?></th>
<?php	
		}
	}
 ?>
			<th width="3%">
				<?php echo JText::_( 'ID' ); ?>
			</th>
	</tr>			
</thead>
	<?php
	$k = 0;
	$n=count( $this->items );
	for ($i=0; $i < $n; $i++)
	{	
	
		$row = &$this->items[$i];
		$checked = JHTML::_('grid.id',   $i, $row->id );
		$link = JRoute::_( 'index.php?option=com_ckforms&controller=ckdata&task=detail&fid='.JRequest::getVar( 'fid', -1 ).'&cid[]='. $row->id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $i+1; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>            
<?php
	$z=count( $this->fields );
	for ($j=0; $j < $z; $j++)
	{
		$rowField = $this->fields[$j];
		if ($rowField->typefield != 'button')
		{
			$prop=$rowField->name;
			$texte = $row->$prop;
			if (strlen($texte) > 255) {
				$texte = substr($texte,0,255)."...";
			}
 ?>
	<td><?php if ($row->$prop != '') { echo "<a href=\"".$link."\">".$texte."</a>"; } else { ?> &nbsp;<?php } ?></td> 
<?php	
		}
	}
 ?>

			<td>
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
</table> 
    <input type="hidden" name="option" value="com_ckforms" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="ckdata" />
    <input type="hidden" name="fid" value="<?php echo JRequest::getVar( 'fid', -1 ) ?>" />
</form>
