<?php
defined('_JEXEC') or die;

$application = JFactory::getApplication();
$document = JFactory::getDocument();

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