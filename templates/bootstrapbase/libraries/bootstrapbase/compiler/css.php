<?php

/**
 * @package     BootstrapBase
 * @subpackage  Compiler
 *
 * @copyright   Copyright (C) 2013-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::import('joomla.filesystem.stream');
JLoader::import('joomla.filesystem.folder');
JLoader::import('joomla.log.log');

JLoader::register('BootstrapBaseCompiler', dirname(__FILE__) . '/../compiler.php');

JLoader::registerNamespace('Less', dirname(__FILE__) . '/../../vendor/oyejorge/less.php/lib');
//JLoader::registerNamespace('Leafo', dirname(__FILE__).'/../../vendor/leafo/scssphp/src');
require_once(dirname(__FILE__).'/../../vendor/leafo/scssphp/scss.inc.php');

use Leafo\ScssPhp\Compiler;

class BootstrapBaseCompilerCss extends BootstrapBaseCompiler {

    const SASS_FILE = '/scss/template.scss';
    const LESS_FILE = '/less/template.less';

    private $paths;
    private $compiler;
    private $frequency;

    public function __construct() {
        parent::__construct();

        $app = JFactory::getApplication();

        JLog::addLogger(array());
        $this->logger = 'bootstrapbase';

        $this->paths = new JRegistry;

        $templatePath = JPATH_THEMES . '/' . $app->getTemplate();
        $template = $app->getTemplate();
        $css = $templatePath . '/css/' . $template;

        $this->paths->set('css.sass', $templatePath . self::SASS_FILE);

        $this->paths->set('css.less', $templatePath . self::LESS_FILE);
        $this->paths->set('css.compressed', $css . '.css');
        $this->paths->set('css.sourcemap', $css . '.css.map');

        $this->compiler = $this->params->get('css_compiler', 'sass');
        $this->frequency = $this->params->get('compile_frequency', 'onchange');
        $this->compilers = $this->params->get("sass_output_formatting", "crunched");
    }

    /**
     * Compiles the CSS source code into a single CSS file.
     */
    public function compile() {
        try {
            $dest = $this->paths->get('css.compressed');
            $compile = $this->frequency;

            $changed = (bool) $this->isCssUpdated();

            JLog::add('CSS cache changed: ' . ((bool) $changed ? 'true' : 'false'), JLog::DEBUG, $this->logger);

            $force = (bool) ($compile == "onpageload");
            $changed = (bool) ($compile == "onchange" && $changed);

            JLog::add('Force CSS compilation: ' . ((bool) $force ? 'true' : 'false'), JLog::DEBUG, $this->logger);
            JLog::add('Compiling CSS: ' . ((bool) $changed ? 'true' : 'false'), JLog::DEBUG, $this->logger);

            if (!JFile::exists($dest) || $changed || $force) {

                if ($this->compiler == 'less') {

                    $generateSourceMap = $this->params->get('generate_css_sourcemap', false);

                    JLog::add('Generate CSS sourcemap: ' . ((bool) $generateSourceMap ? 'true' : 'false'), JLog::DEBUG, $this->logger);

                    $options = array('compress' => true);

                    $cssSourceMapUri = str_replace(JPATH_ROOT, JURI::base(), $this->paths->get('css.sourcemap'));

                    // Build source map with minified code.
                    if ($this->params->get('generate_css_sourcemap', false)) {
                        $options['sourceMap'] = true;
                        $options['sourceMapWriteTo'] = $this->paths->get('css.sourcemap');
                        $options['sourceMapURL'] = $cssSourceMapUri;
                        $options['sourceMapBasepath'] = JPATH_ROOT;
                        $options['sourceMapRootpath'] = JUri::base();
                    } else {
                        JFile::delete($this->paths->get('css.sourcemap'));
                    }

                    $less = new Less_Parser($options);
                    $less->parseFile($this->paths->get('css.less'), JUri::base());

                    $css = $less->getCss();

                    $files = $less->allParsedFiles();
                } else {

                    $formatterName = "Leafo\ScssPhp\Formatter\\" . $this->compilers;

                    $scss = new Compiler();
                    $scss->setFormatter($formatterName);

                    $css = $scss->compile("@import \"".$this->paths->get('css.sass')."\"");
                    $files = array_keys($scss->getParsedFiles());
                }

                JLog::add('Writing CSS to: ' . $dest, JLog::DEBUG, $this->logger);
                JFile::write($dest, $css);

                // update cache.
                $this->updateCache(self::CACHEKEY . '.files.css', $files);
            }
        } catch (Exception $e) {
            JLog::add($e->getMessage(), JLog::ERROR, $this->logger);
            return false;
        }
    }

    /**
     * Check the cache against the file system to determine whether the CSS
     * files have been updated.
     *
     * @return boolean True if the CSS files have been updated, false
     * otherwise.
     */
    private function isCssUpdated() {
        $changed = false;

        $generateSourceMap = $this->params->get('generate_css_sourcemap', false);

        // if the source map still exists but shouldn't be created, just recompile.
        if (JFile::exists($this->paths->get('css.sourcemap')) && !$generateSourceMap) {
            $changed = true;
        }

        // if the source map doesn't exist but should, just recompile.
        if (!JFile::exists($this->paths->get('css.sourcemap')) && $generateSourceMap) {
            $changed = true;
        }

        if (!$changed) {
            $changed = $this->isCacheChanged(self::CACHEKEY . '.files.css');
        }

        return $changed;
    }

}
