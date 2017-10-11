<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2016 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('bootstrap.framework');
JHtml::script('jui/tooltip.min.js', false, true);
$templatePath = JPATH_THEMES.'/'.$this->template.'/';

$application = JFactory::getApplication();
$document = JFactory::getDocument();
$menu = $application->getMenu();

// Output as HTML5
$this->setHtml5(true);

$params = $application->getTemplate(true)->params;

$option = $application->input->getCmd('option', '');
$view = $application->input->getCmd('view', '');
$layout = $application->input->getCmd('layout', '');
$task = $application->input->getCmd('task', '');
$itemid = $application->input->getCmd('Itemid', '');

// @todo Not sure why we set the template with the JDocument language and
// direction because the template is a fully initiated JDocumentHTML
// object as well.
$this->language = $document->language;
$this->direction = $document->direction;

// Set the body class for the overall web page.
$bodyClass = $option.' view-'. $view.
    ($layout ? ' layout-' . $layout : '').
    ($task ? ' task-' . $task : '');

// append the page class suffix to the correct location.
$active = $menu->getActive();

if ($active->id) {
    $bodyClass .= ' '.$active->alias;
} else {
    $bodyClass .= ($itemid ? ' itemid-' . $itemid : '');
}

$bodyClass .= $menu->getParams($active->id)->get('pageclass_sfx', '');

// Set viewport
$this->setMetaData("viewport", "width=device-width,initial-scale=1");

// adjust main content depending on whether right or left modules are being shown.
if ($this->countModules('left') > 0 && $this->countModules('right') > 0) {
    $mainClass = 'both-sidebars';
} elseif ($this->countModules('left') > 0) {
    $mainClass = 'left-sidebar';
} elseif ($this->countModules('right') > 0) {
    $mainClass = 'right-sidebar';
} else {
    $mainClass = 'no-sidebars';
}

$templateUrl = JUri::base().'templates/'.$this->template;

$cssCompiled = '/css/'.$application->getTemplate().'.css';

if (JFile::exists($templatePath.$cssCompiled)) {
    $this->addStylesheet($templateUrl.$cssCompiled);
}

// load additional css files directly from CSS directory. Needs to be removed at some stage.
foreach (JFolder::files($templatePath.'/css/', ".+\.css") as $file) {
    $this->addStylesheet($templateUrl.'/css/'.$file);
}
