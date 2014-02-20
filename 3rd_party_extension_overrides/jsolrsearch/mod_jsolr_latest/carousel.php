<?php
/**
 * @copyright	Copyright (C) 2014 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSolr Latest Items module for Joomla!.

   The JSolr Latest Items module for Joomla! is free software: you can
   redistribute it and/or modify it under the terms of the GNU General Public
   License as published by the Free Software Foundation, either version 3 of
   the License, or (at your option) any later version.

   The JSolr Latest Items module for Joomla! is distributed in the hope
   that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSolr filter module for Joomla!.  If not, see
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have
 * contributed any source code changes.
 * Name							Email
 * Hayden Young					<hayden@knowledgearc.com>
 *
 */

defined('_JEXEC') or die('Restricted access');

$id = "carousel-jsolrlatest-".$module->id;
?>
<div 
	id="<?php echo $id; ?>" 
	class="jsolrlatest<?php echo $moduleclass_sfx; ?> carousel slide" 
	data-ride="carousel">
	<ol class="carousel-indicators">
		<?php for ($i = 0; $i < count($items); $i++) : ?>
		<li data-target="#<?php echo $id; ?>" data-slide-to="<?php echo $i; ?>"<?php echo ($i == 0) ? ' class="active"' : ''; ?>></li>
		<?php endfor; ?>
	</ol>
<?php  ?>
	<div class="carousel-inner">
		<?php 
		foreach ($items as $key=>$item) :
		?>
		<div class="item<?php echo ($key == 0) ? ' active' : ''; ?>">
			<div class="carousel-item">
				<h3><a href="<?php echo JRoute::_($item->link); ?>"><?php echo $item->title; ?></a></h3>
				<div><?php echo $item->author; ?></div>
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