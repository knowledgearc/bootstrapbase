<?php
/**
 * @package     BootstrapBase
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2013-2016 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// initialize the template settings, client side lib loading, page direction, etc.
require_once (JPATH_THEMES.'/'.$this->template.'/initialize.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <jdoc:include type="head" />
    </head>

    <body<?php echo $bodyClass ? ' class="'.$bodyClass.'"' : ''; ?>>
        <header>
            <jdoc:include type="modules" name="navbar" />
            <div id="header-container">
                <jdoc:include type="message" />

                <div id="header-modules">
                    <jdoc:include type="modules" name="header" style="bootstrapified" />
                </div>

                <div id="hero-modules">
                    <jdoc:include type="modules" name="hero" style="bootstrapified" />
                </div>
            </div>
        </header>

        <div id="top">
            <jdoc:include type="modules" name="top" style="bootstrapified" />
        </div>

        <div id="content">
            <?php if (array_search($mainClass, array("left-sidebar", "both-sidebars")) !== false) : ?>
            <aside id="sidebar1" class="sidebar">
                <jdoc:include type="modules" name="left" style="bootstrapified" />
            </aside>
            <?php endif; ?>

            <main role="main" class="<?php echo $mainClass; ?>">
                <div id="above-content-modules">
                    <jdoc:include type="modules" name="above-content" style="bootstrapified" />
                </div>

                <jdoc:include type="module" name="breadcrumbs" title="Breadcrumbs" />

                <jdoc:include type="component" />

                <div id="below-content-modules">
                    <jdoc:include type="modules" name="below-content" style="bootstrapified" />
                </div>
            </main>

            <?php if (array_search($mainClass, array("right-sidebar", "both-sidebars")) !== false) : ?>
            <aside id="sidebar2" class="sidebar">
                <jdoc:include type="modules" name="right" style="bootstrapified" />
            </aside>
            <?php endif; ?>
        </div>

        <div id="bottom">
            <jdoc:include type="modules" name="bottom" style="bootstrapified" />
        </div>

        <footer>
            <div id="footer-container">
                <div id="footer-modules">
                    <jdoc:include type="modules" name="footer" style="bootstrapified" />
                </div>
            </div>

            <jdoc:include type="modules" name="copyright" />
        </footer>

        <jdoc:include type="modules" name="debug" />
    </body>
</html>
