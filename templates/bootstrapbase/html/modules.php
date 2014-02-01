<?php
defined('_JEXEC') or die;

/**
 * Chrome layout with no heading and no container.
 * 
 * @param object $module
 * @param object $params
 * @param array $attribs
 */
function modChrome_basic($module, &$params, &$attribs)
{
	if ($module->content)
	{
		echo $module->content;
	}
}

/**
 * A custom chrome implementing title and content as well as providing all 
 * available CSS class (I.e. both module and header) and HTML tag overrides 
 * for complete customization.
 *
 * @param object $module
 * @param object $params
 * @param array $attribs
 */
function modChrome_custom($module, &$params, &$attribs)
{
	$moduleTag = $params->get('module_tag', 'div');
	$modClassSfx = $params->get('moduleclass_sfx', '');
	$headerTag = htmlspecialchars($params->get('header_tag', 'h3'));	

	if (!empty ($module->content)) : ?>
		<<?php echo $moduleTag; ?><?php ($modClassSfx) ? ' class="'.htmlspecialchars($modClassSfx).'"' : ''; ?>">

		<?php if ((bool)$module->showtitle) :?>		
			<<?php echo $headerTag; ?> class="<?php echo $params->get('header_class'); ?>"><?php echo $module->title; ?></<?php echo $headerTag; ?>>
		<?php endif; ?>

		<?php echo $module->content; ?>
		
		</<?php echo $moduleTag; ?>>
	<?php endif;
}

