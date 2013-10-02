<?php
defined('_JEXEC') or die;

// Set the body class for the overall web page.
$bodyClass = ' '.$option.' view-'. $view.
	($layout ? ' layout-' . $layout : ' no-layout').
	($task ? ' task-' . $task : ' no-task').
	($itemid ? ' itemid-' . $itemid : '');