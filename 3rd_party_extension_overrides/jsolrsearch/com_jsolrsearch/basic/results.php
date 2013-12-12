<?php
/**
 * Provides the base for the search results display.
 * 
 * Loads the form, facet filters, facets, results and pagination templates.
 * 
 * @package		JSolr
 * @subpackage	Search
 * @copyright	Copyright (C) 2012-2013 Wijiti Pty Ltd. All rights reserved.
 * @license     This file is part of the JSolrSearch Component for Joomla!.
 *
 *   The JSolrSearch Component for Joomla! is free software: you can redistribute it 
 *   and/or modify it under the terms of the GNU General Public License as 
 *   published by the Free Software Foundation, either version 3 of the License, 
 *   or (at your option) any later version.
 *
 *   The JSolrSearch Component for Joomla! is distributed in the hope that it will be 
 *   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with the JSolrSearch Component for Joomla!.  If not, see 
 *   <http://www.gnu.org/licenses/>.
 *
 * Contributors
 * Please feel free to add your name and email (optional) here if you have 
 * contributed any source code changes.
 * @author Hayden Young <haydenyoung@wijiti.com>
 * @author Bartłomiej Kiełbasa <bartlomiejkielbasa@wijiti.com>
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

$document = JFactory::getDocument();

$cssClass = 'jsolrsearch-results';
if ($this->items->getFacets() && $this->params->get('facets_embed')) :
	$cssClass .= ' jsolrsearch-embedded-facets';
endif;
?>
<section 
	<?php echo ($this->params->get('o')) ? 'id="'.$this->params->get('o').'"' : ''; ?>
	class="<?php echo $cssClass; ?>">
	<header>
		<?php echo $this->loadTemplate('form'); ?>
	
		<div id="jsolrsearch-applied-filters">
		   <?php echo $this->loadTemplate('appliedfilters'); ?>
		</div>
	</header>

	<?php if (!is_null($this->items)): ?>
		<?php echo $this->loadResultsTemplate(); ?>
	<?php endif ?>
		      
	<footer>
	<?php echo $this->get('Pagination')->getPagesLinks(); ?>
	</footer>
</section>