<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$id = "carousel-newsflash-".$module->id;
?>
<div 
	id="<?php echo $id; ?>" 
	class="newsflash<?php echo $moduleclass_sfx; ?> carousel slide" 
	data-ride="carousel">
	<ol class="carousel-indicators">
		<?php for ($i = 0; $i < count($list); $i++) : ?>
		<li data-target="#<?php echo $id; ?>" data-slide-to="<?php echo $i; ?>"<?php echo ($i == 0) ? ' class="active"' : ''; ?>></li>
		<?php endfor; ?>
	</ol>

	<div class="carousel-inner">
		<?php 
		foreach ($list as $key=>$item) :
			 $images = json_decode($item->images);
		?>
		<div class="item<?php echo ($key == 0) ? ' active' : ''; ?>">
			<img src="<?php echo $images->image_intro; ?>" alt="<?php echo $images->image_intro_alt; ?>"/>
			<div class="carousel-caption">			
				<?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item'); ?>
			</div>		
		</div>
		<?php 
		endforeach;
		?>
	</div>

	<a class="left carousel-control" href="#<?php echo $id; ?>" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left"></span>
	</a>
	<a class="right carousel-control" href="#<?php echo $id; ?>" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right"></span>
	</a>
</div>