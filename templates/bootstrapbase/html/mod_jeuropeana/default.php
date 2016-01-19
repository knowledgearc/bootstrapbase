<?php
/**
 * @package     JEuropeana.Module
 * @copyright   Copyright (C) 2014 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * Contributors
 * Please feel free to add your name and email (optional) here if you have
 * contributed any source code changes.
 * Name                         Email
 * Hayden Young                 <hayden@knowledgearc.com>
 */

defined('_JEXEC') or die('Restricted access');
?>

<?php foreach ($items->items as $item) : ?>

    <div class="row">
        <div class="col-md-2">
            <a href="<?php echo JArrayHelper::getValue($item->link, 0); ?>" class="thumbnail">
                <img src="<?php echo JArrayHelper::getValue($item->edmPreview, 0); ?>" alt="">
            </a>
        </div>
        <div class="col-md-10">
            <h3><a href="<?php echo JArrayHelper::getValue($item->link, 0); ?>"><?php echo JArrayHelper::getValue($item->title, 0); ?></a></h3>
            <?php if (isset($item->dcCreator)) : ?>
            <h4><?php echo implode(', ', $item->dcCreator); ?></h4>
            <?php endif; ?>
        </div>
    </div>

<?php endforeach; ?>