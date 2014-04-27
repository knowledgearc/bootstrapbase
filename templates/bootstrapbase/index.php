<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// initialize the template settings, client side lib loading, page direction, etc.
require_once (JPATH_ROOT.'/templates/'.$this->template.'/initialize.php');

// load the bootstrap support.
require_once (JPATH_ROOT.'/templates/'.$this->template.'/bootstrapify.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

	<head>
		<jdoc:include type="head" />
	</head>

	<body<?php echo $bodyClass ? ' class="'.$bodyClass.'"' : ''; ?>>
		<div class="container">
			<header>
				<jdoc:include type="modules" name="navbar" />
				<jdoc:include type="message" />
			
				<jdoc:include type="modules" name="header" />
				<jdoc:include type="modules" name="hero" />
				<jdoc:include type="modules" name="top" />
			</header>

			<div id="content">
				<aside id="sidebar1" class="sidebar">
					<jdoc:include type="modules" name="left" />
				</aside>
	
				<main role="main" class="<?php echo $mainClass; ?>">				
					<jdoc:include type="modules" name="above-content" />
					<jdoc:include type="module" name="breadcrumbs" title="Breadcrumbs" />		
					<jdoc:include type="component" />		
					<jdoc:include type="modules" name="below-content" />			
				</main>
	
				<aside id="sidebar2" class="sidebar">
					<jdoc:include type="modules" name="right" />
				</aside>
			</div>
			
			<footer>
				<jdoc:include type="modules" name="bottom" />
				<jdoc:include type="modules" name="footer" />
			
				<jdoc:include type="modules" name="debug" />
			</footer>
		</div>		
	</body>
</html>
		