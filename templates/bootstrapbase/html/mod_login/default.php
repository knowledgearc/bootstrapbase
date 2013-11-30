<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-horizontal" role="form">
	<?php if ($params->get('pretext')) : ?>
	<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
	</div>
	<?php endif; ?>

	<div class="login-panel">
		<div id="form-login-username" class="form-group">
			<?php if (!$params->get('usetext')) : ?>
			<div class="input-group">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-user hasTooltip" data-toggle="tooltip" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"></span>
					<label for="modlgn-username" class="sr-only"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
				</span>
				<input id="modlgn-username" type="text" name="username" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
			</div>
			<?php else: ?>
			<label for="modlgn-username" class="control-label"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
			<input id="modlgn-username" type="text" name="username" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
			<?php endif; ?>
		</div>
		<div id="form-login-password" class="form-group">
			<?php if (!$params->get('usetext')) : ?>
			<div class="input-group">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-lock hasTooltip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></span>
					<label for="modlgn-passwd" class="sr-only"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
				</span>
				<input id="modlgn-passwd" type="password" name="password" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
			</div>
			<?php else: ?>
			<label for="modlgn-passwd" class="control-label"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
			<input id="modlgn-passwd" type="password" name="password" class="form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
			<?php endif; ?>
		</div>
		<?php if (count($twofactormethods) > 1): ?>
		<div id="form-login-secretkey" class="control-group">
			<?php if (!$params->get('usetext')) : ?>
			<div class="input-group input-prepend input-append">
				<span class="input-group-addon">
					<span class="icon-star hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>">
					</span>
						<label for="modlgn-secretkey" class="sr-only"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?>
					</label>
				</span>
				<input id="modlgn-secretkey" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
				<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
					<span class="icon-help"></span>
				</span>
			</div>
			<?php else: ?>
			<label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY') ?></label>
			<input id="modlgn-secretkey" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
			<span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
				<span class="icon-help"></span>
			</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="form-login-remember" class="form-group">
			<div class="checkbox">
				<label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label> <input id="modlgn-remember" type="checkbox" name="remember" value="yes"/>
			</div>
		</div>
		<?php endif; ?>
		
		<div id="form-login-submit" class="form-group">
			<button type="submit" tabindex="0" name="Submit" class="btn btn-primary"><?php echo JText::_('JLOGIN') ?></button>
		</div>
		<?php
			$usersConfig = JComponentHelper::getParams('com_users');
			if ($usersConfig->get('allowUserRegistration')) : ?>
		<ul class="unstyled">
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				  <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>

		</ul>
		<?php endif; ?>
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	
	<?php if ($params->get('posttext')) : ?>
	<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
	</div>
	<?php endif; ?>
	
</form>