<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2016 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/*
 * The default Boostrapbase chrome. Uses the module manager Advanced settings
 * to fully control the module wrapper.
 */
function modChrome_bootstrapified($module, &$params, &$attribs) {
    $moduleTag = $params->get('module_tag', 'div');
    $headerTag = htmlspecialchars($params->get('header_tag', 'h3'));
    $bootstrapSize = (int) $params->get('bootstrap_size', 0);
    $moduleClasses = array();

    if ($params->get('moduleclass_sfx')) {
        $moduleClasses[] = htmlspecialchars($params->get('moduleclass_sfx'));
    }

    if ($bootstrapSize != 0) {
        $moduleClasses[] = 'module-'.$bootstrapSize;
    }

    $moduleClass = count($moduleClasses) > 0 ? " class=\"".implode(" ", $moduleClasses)."\"" : "";

    // Temporarily store header class in variable
    $headerClass = $params->get('header_class');
    $headerClass = !empty($headerClass) ? ' class="'.htmlspecialchars($headerClass).'"' : '';

    if (!empty($module->content)) : ?>
        <<?php echo $moduleTag; ?><?php echo $moduleClass; ?>>

        <?php if ((bool) $module->showtitle) :?>
            <<?php echo $headerTag.$headerClass.'>' . $module->title; ?></<?php echo $headerTag; ?>>
        <?php endif; ?>

        <?php echo $module->content; ?>

        </<?php echo $moduleTag; ?>>
    <?php endif;
}
