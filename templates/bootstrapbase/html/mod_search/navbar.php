<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2014 KnowledgeARC Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" class="navbar-form <?php echo $moduleclass_sfx; ?>" 
role="search">
	<div class="input-group">
		<input 
			name="q" 
			id="q" 
			maxlength="<?php echo $maxlength; ?>"
			class="form-control" 
			type="text" 
			size="<?php echo $width; ?>" 
			placeholder="<?php echo $text; ?>"/>
			
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
		
		<div class="input-group-btn">
			<button class="btn btn-default" type="submit">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</div>
	</div>
</form>