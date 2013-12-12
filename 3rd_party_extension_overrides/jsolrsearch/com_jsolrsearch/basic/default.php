<?php
/**
 * Default search page.
 * 
 * Override to edit the JSolrSearch home page.
 * 
 * @package		JSolr
 * @subpackage	Search
 * @copyright	Copyright (C) 2012 Wijiti Pty Ltd. All rights reserved.
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
<form action="<?php echo JRoute::_("index.php"); ?>" method="get" name="adminForm" class="form-validate jsolr-query-form" id="jsolr-search-form">
	<input type="hidden" name="option" value="com_jsolrsearch"/>
	<input type="hidden" name="task" value="search"/>
					
	<div class="query">
	<?php foreach ($this->get('Form')->getFieldset('query') as $field): ?>
		<?php echo $form->getInput($field->fieldname); ?>
	<?php endforeach;?>			
	</div>
	
	<button type="submit" class="button"><?php echo JText::_("COM_JSOLRSEARCH_BUTTON_SUBMIT"); ?></button>
</form>