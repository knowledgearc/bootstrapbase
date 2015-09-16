<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$classes = array();
$attrs = array();
$caret = '';

if ($item->anchor_css) {
	$classes[] = $item->anchor_css;
}

if ($item->deeper == 1) {
	$classes[] = 'dropdown-toggle';
	$attrs['data-toggle'] = 'dropdown';
	$caret = '<span class="caret"></span>';
}

if ($item->anchor_title) {
	$attrs['title'] = $item->anchor_title;
}

if ($item->menu_image) {
		$item->params->get('menu_text', 1) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
} else {
	$linktype = $item->title;
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) {
	case 1:
		$attrs['target'] = '_blank';
		break;

	case 2:
		// window.open
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
		$attrs['onclick'] = "window.open(this.href,'targetWindow','".$options."');return false;";
		break;
}

if (count($classes) > 0) {
	$class = 'class="'.implode(' ', $classes).'" ';
}

$attr = '';
foreach ($attrs as $key=>$value) {
	$attr .= $key.'="'.$value.'" ';
}
?>
<a href="<?php echo $flink; ?>" <?php echo $class; ?><?php echo trim($attr); ?>><?php echo $linktype; ?><?php echo $caret; ?></a>
