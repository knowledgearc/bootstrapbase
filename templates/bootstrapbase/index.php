<?php
defined('_JEXEC') or die;

require_once ($this->baseurl.'/templates/'.$this->template.'/variables.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

	<head>
		<jdoc:include type="head"/>
	</head>

	<body class="site<?php $bodyClass; ?>">
		<jdoc:include type="module" name="mainmenu" title="Scrolling Main Menu"/>
		<jdoc:include type="message"/>
		
		<jdoc:include type="modules" name="header" />
		<jdoc:include type="module" name="hero" title="Hero"/>
		<jdoc:include type="modules" name="top"/>
		<jdoc:include type="modules" name="above-content"/>
		<jdoc:include type="module" name="breadcrumb" title="Breadcrumbs"/>
		
		<jdoc:include type="component"/>
		
		<jdoc:include type="modules" name="below-content"/>
		
		<jdoc:include type="modules" name="left"/>
		<jdoc:include type="modules" name="right"/>
		
		<jdoc:include type="modules" name="bottom"/>
		<jdoc:include type="modules" name="footer"/>
		
		<jdoc:include type="modules" name="debug" />		
	</body>
</html>
		