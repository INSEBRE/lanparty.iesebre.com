<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
    	<textarea name="ckcss" id="ckcss" wrap="wrap" style="width:98%;height:300px"><?php echo $this->css; ?></textarea>         
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_ckforms" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="cktools" />

</form>

