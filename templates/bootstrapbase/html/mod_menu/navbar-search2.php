<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$tag = '';
if ($params->get('tag_id') != null) {
	$tag = $params->get('tag_id').'';
	$target = 'navbar-'.$tag.'-collapse';
	$tag = ' id="'.$tag.'"';
} else {
	$target = 'navbar-'.$module->id.'-collapse';
}

// load the search box.
$search = null;
$searches = JModuleHelper::getModules('navbar-search');

if (count($searches))
{
	$search = JArrayHelper::getValue($searches, 0);
	$renderer = JFactory::getDocument()->loadRenderer('module');
	$search = $renderer->render($search);
}

// load the logo.
$templateParams   = JFactory::getApplication()->getTemplate(true)->params;

$logo = <<< HTML
<img
    src="{$templateParams->get('logo')}"
    alt="{JFactory::getConfig()->get('sitename')}"/>
HTML;

$brands = JModuleHelper::getModules('navbar-brand');

if (count($brands))
{
	$brand = JArrayHelper::getValue($brands, 0);
	$renderer = JFactory::getDocument()->loadRenderer('module');
	$logo = $renderer->render($brand);
}
?>
<nav<?php echo $tag; ?> class="navbar<?php echo $class_sfx; ?>" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button
				type="button"
				class="navbar-toggle"
				data-toggle="collapse"
				data-target=".<?php echo $target; ?>">

				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>

		<div class="navbar-search2-logo">
			<?php if ($logo) : ?>
			<a href="<?php echo JUri::base(); ?>"><?php echo $logo; ?></a>
			<?php endif; ?>
		</div>

		<div class="navbar-search2-navs">
			<div class="navbar-search2-search">
				<?php echo $search; ?>
			</div>

			<div class="navbar-search2-menu collapse navbar-collapse <?php echo $target; ?>">
				<ul class="nav navbar-nav">
					<?php
					foreach ($list as $i => &$item) {
						$class = 'item-'.$item->id;

						if ($item->id == $active_id) {
							$class .= ' current';
						}

						if (in_array($item->id, $path)) {
							$class .= ' active';
						} elseif ($item->type == 'alias') {
							$aliasToId = $item->params->get('aliasoptions');

							if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
								$class .= ' active';
							} elseif (in_array($aliasToId, $path)) {
								$class .= ' alias-parent-active';
							}
						}

						if (!empty($class)) {
							$class = ' class="'.trim($class) .'"';
						}

						echo '<li'.$class.'>';

						// Render the menu item.
						switch ($item->type) {
							case 'separator':
							case 'url':
							case 'component':
							case 'heading':
								require JModuleHelper::getLayoutPath('mod_menu', 'navbar_'.$item->type);
								break;

							default:
								require JModuleHelper::getLayoutPath('mod_menu', 'navbar_url');
								break;
						}

						// The next item is deeper.
						if ($item->deeper) {
							echo '<ul class="dropdown-menu">';
						} elseif ($item->shallower) {
							echo '</li>';
							echo str_repeat('</ul></li>', $item->level_diff);
						} else {
							echo '</li>';
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</nav>