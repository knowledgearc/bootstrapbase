<?php
defined('_JEXEC') or die;

require_once (JPATH_ROOT.'/templates/'.$this->template.'/variables.php');

JHtml::_('bootstrap.framework');
$this->addStylesheet(JURI::base().'/templates/'.$this->template.'/css/template.min.css');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

	<head>
		<jdoc:include type="head" />
	</head>

	<body class="<?php $bodyClass; ?>">
		<jdoc:include type="module" name="menu" title="Main Menu" />
		<jdoc:include type="message" />
		
		<jdoc:include type="modules" name="header" />
		<jdoc:include type="module" name="hero" title="Hero" />
		<jdoc:include type="modules" name="top" />
		
		<jdoc:include type="modules" name="above-content" />
		<jdoc:include type="module" name="breadcrumbs" title="Breadcrumbs" />		
		<jdoc:include type="component" />		
		<jdoc:include type="modules" name="below-content" />
		
		<jdoc:include type="modules" name="left" />
		<jdoc:include type="modules" name="right" />
		
		<jdoc:include type="modules" name="bottom" />
		<jdoc:include type="modules" name="footer" />
		
		<jdoc:include type="modules" name="debug" />		
	</body>
</html>
		