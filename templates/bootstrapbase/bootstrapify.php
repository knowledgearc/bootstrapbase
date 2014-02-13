<?php
JHtml::_('bootstrap.framework');

$js = <<<JS
JS;

// load tooltips
$js .= <<<JS
(function ($) {
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
})(jQuery)
JS;

$this->addScriptDeclaration($js);

$this->addStylesheet(JURI::base().'templates/'.$this->template.'/css/template.min.css');

if ($googleFont) {
	$this->addStylesheet('http://fonts.googleapis.com/css?'.implode('&', $googleFont));
}