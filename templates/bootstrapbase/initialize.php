<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$application = JFactory::getApplication();
$document = JFactory::getDocument();

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
$bodyClass = ' '.$option.' view-'. $view.
	($layout ? ' layout-' . $layout : ' no-layout').
	($task ? ' task-' . $task : ' no-task').
	($itemid ? ' itemid-' . $itemid : '');

// unload mootools if specified.
if ($params->get('mootools_core_load', 1) != 1) {
	$headers = $this->getHeadData();
	
	$scripts = JArrayHelper::getValue($headers, 'scripts');
	
	foreach (preg_grep('/.*mootools.*\.js$/',array_keys($scripts)) as $item) {
		unset($headers['scripts'][$item]);
	}
	
	$this->setHeadData($headers);
}

if ($params->get('mootools_more_load', 0) != 1) {
	$headers = $this->getHeadData();

	$scripts = JArrayHelper::getValue($headers, 'scripts');

	foreach (preg_grep('/.*mootools-more.*\.js$/',array_keys($scripts)) as $item) {
		unset($headers['scripts'][$item]);
	}
	
	foreach (preg_grep('/.*validate.*\.js$/',array_keys($scripts)) as $item) {
		unset($headers['scripts'][$item]);
	}
	
	$this->setHeadData($headers);
}

// setup google fonts if required.
$googleFont = null;

if ($params->get('googlefonts_load') == 1) {
	$googleFont = array();
	
	if (trim($params->get('googlefonts_load_family'))) {
		$googleFont[] = 'family='.$params->get('googlefonts_load_family');
		
		if (trim($params->get('googlefonts_load_subsets'))) {
			$googleFont[] = 'subset='.$params->get('googlefonts_load_subsets');
		} elseif (trim($params->get('googlefonts_load_text'))) {
			$googleFont[] = 'text='.$params->get('googlefonts_load_text');
		}

		if (trim($params->get('googlefonts_load_effect'))) {
			$googleFont[] = 'effect='.$params->get('googlefonts_load_effect');
		}
	}
}

// adjust main content depending on whether right or left modules are being 
//shown.
if ($this->countModules('left') > 0 && $this->countModules('right') > 0) {
	$mainClass = 'both-sidebars';
} elseif ($this->countModules('left') > 0) {
	$mainClass = 'left-sidebar';
} elseif ($this->countModules('right') > 0) {
	$mainClass = 'right-sidebar';
} else {
	$mainClass = 'no-sidebars';
}