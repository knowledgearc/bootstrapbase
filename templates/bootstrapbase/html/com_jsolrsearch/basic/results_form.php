<?php
/**
 * Provides the search form within the search results display so that a user 
 * can modify the current search without having to start over.
 * 
 * Copy this file to override the layout and style of the search results form.
 * 
 * @copyright	Copyright (C) 2012-2013 Wijiti Pty Ltd. All rights reserved.
 * @copyright	Copyright (C) 2013 KnowledgeARC Ltd. All rights reserved.
 * @license     This file is part of the JSolrSearch Component for Joomla!.

   The JSolrSearch Component for Joomla! is free software: you can redistribute it 
   and/or modify it under the terms of the GNU General Public License as 
   published by the Free Software Foundation, either version 3 of the License, 
   or (at your option) any later version.

   The JSolrSearch Component for Joomla! is distributed in the hope that it will be 
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the JSolrSearch Component for Joomla!.  If not, see 
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * Name							Email
 * Hayden Young					<haydenyoung@wijiti.com>
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_('behavior.formvalidation');

$form = $this->get('Form');
?>
<form 
	action="<?php echo JRoute::_("index.php"); ?>" 
	method="get" 
	name="adminForm" 
	id="jsolr-search-form" 
	class="jsolr-results-form" 
	role="form">
	<input type="hidden" name="option" value="com_jsolrsearch"/>
	<input type="hidden" name="task" value="search"/>
	
	<?php if (JFactory::getApplication()->input->get('o', null)) : ?>
	<input type="hidden" name="o" value="<?php echo JFactory::getApplication()->input->get('o'); ?>"/>
	<?php endif; ?>
	
	<fieldset>
		<div class="query">
		<?php foreach ($this->get('Form')->getFieldset('query') as $field): ?>
			<?php echo $form->getInput($field->fieldname); ?>
		<?php endforeach;?>
			
			<span>
				<button type="submit"></button>
			</span>
		</div>	
	</fieldset>
	
	<!-- Output the hidden form fields for the various selected facet filters. -->
	<?php foreach ($this->get('Form')->getFieldset('facets') as $field): ?>
		<?php if (trim($field->value)) : ?>
			<?php echo $this->form->getInput($field->fieldname); ?>
		<?php endif; ?>
	<?php endforeach;?>	

	<nav>			
		<ul class="extensions">
			<?php
			$components = $this->get('Extensions');
			
			for ($i = 0; $i < count($components); ++$i): 
				$isSelected = ($components[$i]['plugin'] == JFactory::getApplication()->input->get('o', null, 'cmd')) ? true : false;
			
				echo '<li'.($isSelected ? ' class="active"' : '').'>';
			
				echo JHTML::_(
					'link', 
					$components[$i]['uri'], 
					$components[$i]['name'], 
					array(
						'data-category'=>$components[$i]['plugin'])); 

				echo '</li>';
        	endfor 
        	?>
			
			<!-- Disable advanced search if facets are available (for now). -->
			<?php if (!$this->items->getFacets()) : ?>
			<li class="options">
				<button type="button" data-toggle="dropdown">
					<i></i>
				</button>
				<ul role="menu">
					<li><a href="<?php echo JRoute::_(JSolrSearchFactory::getAdvancedSearchRoute()); ?>">Advanced search</a></li>				
				</ul>
			</li>
			<?php endif; ?>
		</ul>
    </nav>

	<ul class="tools">
	<?php foreach ($this->get('Form')->getFieldset('tools') as $field): ?>
		<?php if (strtolower($field->type) != 'jsolr.advancedfilter') : ?>
		<li class="dropdown"><?php echo $this->form->getInput($field->name); ?></li>
		<?php else : ?>
		<?php echo $this->form->getInput($field->name); ?>
		<?php endif; ?>
	<?php endforeach;?>
	</ul>

	<?php echo JHTML::_('form.token'); ?>
</form>