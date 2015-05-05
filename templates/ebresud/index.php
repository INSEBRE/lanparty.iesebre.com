<?php
/**
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
$url = clone(JURI::getInstance());
$path = $this->baseurl.'/templates/'.$this->template;
$showColumn = ($this->countModules('left')) or (JRequest::getCmd('layout') != 'form') or (JRequest::getCmd('task') != 'edit');
$showWelcome = ($this->countModules('user5'));
?>
<?php echo '<?xml version="1.0" encoding="utf-8"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $path ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $path ?>/css/constant.css" type="text/css" />
<script type="text/javascript" src="<?php echo $path; ?>/scripts/jquery.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>/scripts/script.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	
	jQuery('#parallax').jparallax({});
	$(document).pngFix();

});

</script>

</head>
<body id="body">
<div id="parallax">
   <div class="layer-1"></div>
   <div class="layer-2"></div>
   <div class="layer-3"></div>
   <div class="layer-4"></div>
</div>
<div id="header">
	<div class="main">
		
		<div id="top">
			<div id="links"><a href="https://maps.google.com/maps?q=Av.+de+Crist%C3%B2fol+Colom,+34,+43500+Tortosa,+Tarragona,+Catalonia,+Spain&hl=en&ie=UTF8&sll=40.813866,0.51698&sspn=0.007137,0.016512&t=h&hnear=Avinguda+de+Crist%C3%B2fol+Colom,+34,+43500+Tortosa,+Tarragona,+Spain&z=17" target="_blank">Com arribar?</a>  &nbsp; | &nbsp; <a href="https://www.facebook.com/LanPartyIesEbre" target="_blank" >Facebook</a></div>
			<div id="topmenu">
				<jdoc:include type="modules" name="user3" />
			</div>
		</div>
		<div id="header-bg"></div>
		<div class="content">
	        <div id="logo-container">
                <h1 id="logo" title="Ebre Sud Lan Party">
                    <a href="<?php echo $_SERVER['PHP_SELF']?>"><img src="<?php echo $path; ?>/images/logo.png" style="width:250px; height:172x;" alt="logo" /></a>
                </h1>
            </div>
        </div>
	</div>
</div>
<div id="mid">
	<div class="main">
		<div class="mid-bottom2">
			<div id="user1">
				<div class="space">
					<div class="width">
						<?php if ($this->getBuffer('message')) : ?>
						<div class="error">
							<h2><?php echo JText::_('Message'); ?></h2>
							<jdoc:include type="message" />
						</div>
						<?php endif; ?>
						<jdoc:include type="component" />
					</div>
				</div>
			</div>
			<div id="user2">
				<jdoc:include type="modules" name="user2" style="user" />
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>
<div class="mid-bottom">
	<div class="main">&nbsp;</div>
</div>
<div id="level1">
	<div class="main">
		<div id="user4">
			<jdoc:include type="modules" name="user4" style="user" />
		</div>
		<div id="user5">
			<jdoc:include type="modules" name="user5" style="user" />
		</div>
	</div>
</div>
<?php if ($showColumn) : ?>
<div id="content">
	<div class="main">
		<div class="indent">
			<div class="width">
				<div id="left">
					<jdoc:include type="modules" name="left" style="user" />
				</div>
				<div id="right">
					<jdoc:include type="modules" name="right" style="user" />
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<div id="footer">
	<div class="main"><div class="space"><a href="http://www.visualweb.es">Dise&ntilde;o Web</a></div></div>
</div>

<jdoc:include type="modules" name="debug" />
</body>
</html>
