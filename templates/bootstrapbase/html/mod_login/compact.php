<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="mod-login" class="compact" role="form">
	<?php if ($params->get('pretext')) : ?>
	<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
	</div>
	<?php endif; ?>

	<div id="username-field" class="form-field">
		<?php if (!$params->get('usetext')) : ?>
		<span class="form-field-addon">
			<span class="form-field-addon-icon" data-toggle="tooltip" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"></span>
			<label for="username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
		</span>
		<input id="username" type="text" name="username" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
		<?php else: ?>
		<label for="username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
		<input id="username" type="text" name="username" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
		<?php endif; ?>

		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<span class="form-field-help" data-toggle="tooltip" title="<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?>">
			<a class="form-field-help-icon" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><i></i></a>
		</span>
		<?php endif; ?>
	</div>

	<div id="password-field" class="form-field">
		<?php if (!$params->get('usetext')) : ?>
		<span class="form-field-addon">
			<span class="form-field-addon-icon" data-toggle="tooltip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></span>
			<label for="password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
		</span>
		<input id="password" type="password" name="password" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
		<?php else: ?>
		<label for="password"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
		<input id="password" type="password" name="password" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
		<?php endif; ?>

		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<span class="form-field-help" data-toggle="tooltip" title="<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?>">
			<a class="form-field-help-icon" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><i></i></a>
		</span>
		<?php endif; ?>
	</div>

	<?php if (count($twofactormethods) > 1): ?>
	<div id="secretkey-field" class="form-field">
		<?php if (!$params->get('usetext')) : ?>
		<span class="form-field-addon">
			<span class="form-field-addon-icon" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
				<label for="secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
			</span>
		</span>
		<input id="secretkey" type="text" name="secretkey" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
		<?php else: ?>
		<label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY') ?></label>
		<input id="modlgn-secretkey" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY') ?>" />
		<?php endif; ?>
		<span class="form-field-help" data-toggle="tooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
			<span class="form-field-help-icon"><i></i></span>
		</span>
	</div>
	<?php endif; ?>

	<div id="buttons">
		<button type="submit" tabindex="0" name="Submit"><?php echo JText::_('JLOGIN') ?></button>
	</div>

	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<div id="rememberme">
		<label>
			<input id="remember" type="checkbox" name="remember" value="yes"/>
			<?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?>
		</label>
	</div>
	<?php endif; ?>

	<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
	<p class="registration-link">
		<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
		<?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>
	</p>

	<?php endif; ?>
	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>

	<?php if ($params->get('posttext')) : ?>
	<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
	</div>
	<?php endif; ?>

</form>
