<?php defined('_JEXEC') or die; ?>

<div class="componentheading">
	<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?>
</div>

<div class="article_indent">
	<div class="width">
		<form action="index.php?option=com_user&amp;task=requestreset" method="post" class="josForm form-validate">
			<dl class="contentpane">
				<dt>
					<?php echo JText::_('RESET_PASSWORD_REQUEST_DESCRIPTION'); ?>
				</dt>
				<dd>
					<table style="width:auto">
						<tr>
							<td class="description">
								<label for="email" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_EMAIL_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_EMAIL_TIP_TEXT'); ?>"><?php echo JText::_('Email Address'); ?>:</label>
							</td>
							<td class="input-field">
								<input id="email" name="email" type="text" class="required validate-email" />
							</td>
							<td class="button-field">
								<button type="submit" class="validate"><?php echo JText::_('Submit'); ?></button>
							</td>
						</tr>
					</table>
				</dd>
			</dl>
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>