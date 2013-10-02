<?php
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);

$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date') || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
?>
<article itemscope itemtype="http://schema.org/Article">
	<header>
		<?php if ($this->params->get('show_page_heading') && $params->get('show_title')) : ?>
		<h1 itemprop="name headline"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		<?php endif; ?>
		
		<?php
		if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative) :
			echo $this->item->pagination;
		endif;
		?>

		<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
		<h2 itemprop="<?php echo $this->params->get('show_page_heading') ? "alternativeHeadline" : "name headline"; ?>">
			<?php if ($this->item->state == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
			<?php endif; ?>
                        
			<?php if ($params->get('show_title')) : ?>
			<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
				<a href="<?php echo $this->item->readmore_link; ?>" itemprop="url"><?php echo $this->escape($this->item->title); ?></a>
				<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
			<?php endif; ?>
		</h2>
		<?php endif; ?>	
		
		<?php if (!$this->print) : ?>
			<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
			<div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
			<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
			<ul class="dropdown-menu">
				<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
				<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
				<?php endif; ?>
			</ul>
			</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="pull-right">
			<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
			</div>
		<?php endif; ?>
		
		<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<dl class="article-info">
			<dt><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>

			<?php if ($this->item->created) : ?>
			<dd itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="author">
				<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
				
				<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
					<?php
					$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
					$menu = JFactory::getApplication()->getMenu();
					$item = $menu->getItems('link', $needle, true);
					$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
					?>
					<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), '<span itemprop="name">'.$author.'</span>', array('itemprop'=>'url'))); ?>
				<?php else: ?>
					<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', '<span itemprop="name">'.$author.'</span>'); ?>
				<?php endif; ?>
			</dd>
			<?php endif; ?>

			<?php if ($params->get('show_publish_date')) : ?>
			<dd class="published">
				<span class="icon-calendar"></span><?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', '<time itemprop="datePublished" datetime="'.JFactory::getDate($this->item->publish_up)->toISO8601().'">'.JFactory::getDate($this->item->publish_up)->format(JText::_('DATE_FORMAT_LC2')).'</time>'); ?>
			</dd>
			<?php endif; ?>

			<?php if ($info == 0) : ?>
				<?php if ($params->get('show_modify_date')) : ?>
				<dd class="modified">
					<span class="icon-calendar"></span><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', '<time itemprop="dateModified" datetime="'.JFactory::getDate($this->item->modified)->toISO8601().'">'.JFactory::getDate($this->item->modified)->format(JText::_('DATE_FORMAT_LC2')).'</time>'); ?>
				</dd>
				<?php endif; ?>

				<?php if ($params->get('show_create_date')) : ?>
				<dd class="create">
					<span class="icon-calendar"></span><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', '<time itemprop="dateCreated" datetime="'.JFactory::getDate($this->item->created)->toISO8601().'">'.JFactory::getDate($this->item->created)->format(JText::_('DATE_FORMAT_LC2')).'</time>'); ?>
				</dd>
				<?php endif; ?>

				<?php if ($params->get('show_hits')) : ?>
				<dd class="hits">
					<span class="icon-eye-open"></span><span itemprop="interactionCount"><?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?></span>
				</dd>
				<?php endif; ?>
			<?php endif; ?>
		</dl>
		<? endif; ?>
		
		<!-- Print out to meta fields if user would like to hide any header information. -->
		<?php if (!$params->get('show_publish_date')) : ?>
		<meta itemprop="datePublished" content="<?php echo JFactory::getDate($this->item->publish_up)->toISO8601(); ?>"/>
		<?php endif; ?>
		
		<?php if (!$params->get('show_modify_date')) : ?>
		<meta itemprop="dateModified" content="<?php echo JFactory::getDate($this->item->modified)->toISO8601(); ?>"/>
		<?php endif; ?>
		
		<?php if (!$params->get('show_create_date')) : ?>
		<meta itemprop="dateCreated" content="<?php echo JFactory::getDate($this->item->created)->toISO8601(); ?>"/>
		<?php endif; ?>
	</header>

	<?php if ($params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	<?php endif; ?>
	
	<?php 
	if (!$params->get('show_intro')) : 
		echo $this->item->event->afterDisplayTitle; 
	endif;
	
	echo $this->item->event->beforeDisplayContent;

	if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position))) || (empty($urls->urls_position) && (!$params->get('urls_position')))) :
		echo $this->loadTemplate('links');
	endif;
	?>
	
	<?php if ($params->get('access-view')):?>
		<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
			<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
			<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image">
				<img
					<?php 
					if ($images->image_fulltext_caption):
						echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) . '"';
					endif; 
					?>
					src="<?php echo htmlspecialchars($images->image_fulltext); ?>" 
					alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"
					itemprop="image"/>
			</div>
		<?php endif; ?>
		
		<?php
		if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
			echo $this->item->pagination;
		endif;
		?>
		
		<?php 
		if (isset ($this->item->toc)) :
			echo $this->item->toc;
		endif;
		?>
		
		<div itemprop="articleBody"><?php echo $this->item->text; ?></div>
	
		<?php 
		if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative) :
			echo $this->item->pagination;
		endif;
		?>

		<?php 
		if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) :
			echo $this->loadTemplate('links');
		endif;

		// Optional teaser intro text for guests
	elseif ($params->get('show_noauth') == true && $user->get('guest')) :
	?>
		<div itemprop="description"><?php echo $this->item->introtext; ?></div>
		
		<?php
		//Optional link to let them register to see the whole article.
		if ($params->get('show_readmore') && $this->item->fulltext != null) :
			$link1 = JRoute::_('index.php?option=com_users&view=login');
			$link = new JUri($link1);
		?>
		<p class="readmore">
			<a href="<?php echo $link; ?>">
			<?php $attribs = json_decode($this->item->attribs); ?>
			<?php
			if ($attribs->alternative_readmore == null) :
				echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
			elseif ($readmore = $this->item->alternative_readmore) :
				echo $readmore;
				if ($params->get('show_readmore_title', 0) != 0) :
					echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif;
			elseif ($params->get('show_readmore_title', 0) == 0) :
				echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
			else :
				echo JText::_('COM_CONTENT_READ_MORE');
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif; ?>
			</a>
		</p>
		<?
		endif;
	endif;
	
	if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
		echo $this->item->pagination;
	endif;

	echo $this->item->event->afterDisplayContent;
	?>
</article>