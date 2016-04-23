<?php

/**
 * @package     BootstrapBase
 * @subpackage  Compiler
 *
 * @copyright   Copyright (C) 2013-2016 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::import('joomla.filesystem.stream');
JLoader::import('joomla.filesystem.folder');
JLoader::import('joomla.log.log');

JLoader::register('BootstrapBaseCompiler', dirname(__FILE__).'/../compiler.php');

JLoader::registerNamespace('Less', dirname(__FILE__) .'/../../vendor/oyejorge/less.php/lib');
//JLoader::registerNamespace('Leafo', dirname(__FILE__).'/../../vendor/leafo/scssphp/src');
require_once(dirname(__FILE__).'/../../vendor/leafo/scssphp/scss.inc.php');

use Leafo\ScssPhp\Compiler;

class BootstrapBaseCompilerCss extends BootstrapBaseCompiler {

    const LOGGER = 'bootstrapbase';

    private $paths;
    private $compiler;
    private $frequency;
    private $formatting;

    public function __construct() {
        parent::__construct();

        $app = JFactory::getApplication();

        JLog::addLogger(array());

        $this->compiler = $this->params->get('css_compiler', 'scss');
        $this->frequency = $this->params->get('compile_frequency', 'onchange');
        $this->formatting = $this->params->get("scss_output_formatting", "crunched");

        $this->paths = new JRegistry;

        $templatePath = JPATH_THEMES.'/'.$app->getTemplate();
        $template = $app->getTemplate();

        $in = $templatePath.'/'.$this->compiler.'/template.'.$this->compiler;
        $out = $templatePath.'/css/'.$template.'.css';

        $this->paths->set('css.in', $in);
        $this->paths->set('css.out', $out);
        $this->paths->set('css.sourcemap', $out.'.map');
    }

    /**
     * Compiles the CSS source code into a single CSS file.
     */
    public function compile() {
        try {
            $dest = $this->paths->get('css.out');
            $compile = $this->frequency;

            $changed = (bool)$this->isCssUpdated();

            JLog::add('CSS cache changed: '.((bool)$changed ? 'true' : 'false'), JLog::DEBUG, self::LOGGER);

            $changed = (bool)(($compile == 2) || ($compile == 1 && $changed));

            $doCompile = (!JFile::exists($dest) || $changed);

            JLog::add('Force CSS compilation: '.($compile == 2 ? 'true' : 'false'), JLog::DEBUG, self::LOGGER);
            JLog::add('CSS file exists: '.((bool)JFile::exists($dest) ? 'true' : 'false'), JLog::DEBUG, self::LOGGER);
            JLog::add('Compiling CSS: '.((bool)$doCompile ? 'true' : 'false'), JLog::DEBUG, self::LOGGER);

            if ($doCompile) {
                if ($this->compiler == 'less') {
                    $generateSourceMap = $this->params->get('generate_css_sourcemap', false);

                    JLog::add('Generate CSS sourcemap: '.((bool)$generateSourceMap ? 'true' : 'false'), JLog::DEBUG, self::LOGGER);

                    $options = array('compress'=>true);

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
                    $less->parseFile($this->paths->get('css.in'), JUri::base());
                    $css = $less->getCss();
                    $files = $less->allParsedFiles();
                } else {
                    $formatter = "Leafo\ScssPhp\Formatter\\".$this->formatting;
                    $this->cache->store($this->formatting, self::CACHEKEY.'.scss.formatter');

                    $scss = new Compiler();
                    $scss->setFormatter($formatter);
                    $css = $scss->compile('@import "'.$this->paths->get('css.in').'";');

                    $files = array_keys($scss->getParsedFiles());
                }

                JLog::add('Writing CSS to: '.$dest, JLog::DEBUG, self::LOGGER);
                JFile::write($dest, $css);

                // update cache.
                $this->updateCache(self::CACHEKEY.'.files.css', $files);
                $this->cache->store($this->compiler, self::CACHEKEY.'.compiler');
            }
        } catch (Exception $e) {
            JLog::add($e->getMessage(), JLog::ERROR, self::LOGGER);
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

        if ($this->compiler == 'less') {
            $generateSourceMap = $this->params->get('generate_css_sourcemap', false);

            // if the source map still exists but shouldn't be created, just recompile.
            if (JFile::exists($this->paths->get('css.sourcemap')) && !$generateSourceMap) {
                $changed = true;
            }

            // if the source map doesn't exist but should, just recompile.
            if (!JFile::exists($this->paths->get('css.sourcemap')) && $generateSourceMap) {
                $changed = true;
            }
        } else {
            if (!$changed) {
                if ($this->cache->get(self::CACHEKEY.'.scss.formatter') !== $this->formatting) {
                    $changed = true;
                }
            }
        }

        if (!$changed) {
            if ($this->cache->get(self::CACHEKEY.'.compiler') !== $this->compiler) {
                $changed = true;
            }
        }

        if (!$changed) {
            $changed = $this->isCacheChanged(self::CACHEKEY.'.files.css');
        }

        return $changed;
    }

}
