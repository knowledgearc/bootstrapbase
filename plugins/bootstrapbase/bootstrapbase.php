<?php
/**
 * @package     BootstrapBase.Plugin
 * @subpackage  System
 *
 * @copyright   Copyright (C) 2013-2015 KnowledgeARC Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

JLoader::import('joomla.filesystem.stream');
JLoader::import('joomla.log.log');

JLoader::register('JSMinPlus', JPATH_PLUGINS.'/system/jsminify/jsminplus.php');

/**
 * Compile LESS files and minify Javscript on-the-fly.
 * Less compiler less.php; see http://github.com/oyejorge/less.php
 */
class PlgSystemBootstrapbase extends JPlugin
{
    const LESS_FILE = '/less/template.less';
    const CACHEKEY = 'bootstrap';

    private $paths;

    private $javascripts;

    private $cache;

    public function __construct($subject, $config = array())
    {
        parent::__construct($subject, $config);

        JLog::addLogger(array());
        $this->logger = 'bootstrapbasesystemplugin';

        $this->paths = new JRegistry;

        $templatePath = JPATH_THEMES.'/'.$this->getTemplate();
        $template = $this->getTemplate();
        $css = $templatePath.'/css/'.$template;
        $js = $templatePath.'/js/jui/'.$this->getTemplate();

        $this->paths->set('css.less', $templatePath.self::LESS_FILE);
        $this->paths->set('css.compressed', $css.'.min.css');
        $this->paths->set('css.sourcemap', $css.'.css.map');

        $this->paths->set('js.minified', $js.'.min.js');

        $this->cache = JFactory::getCache(self::CACHEKEY, '');
        $this->cache->setCaching(true);
    }

    /**
     * Compiles the LESS code into a single CSS file.
     */
    private function compileCss()
    {
        try {
            JLoader::registerNamespace('Less', __DIR__.'/compilers/oyejorge/less.php/lib');

            $dest = $this->paths->get('css.compressed');
            $force = (bool)$this->params->get('force_less_compilation', false);
            $changed = (bool)$this->isLessUpdated();
            $generateSourceMap = $this->params->get('generate_css_sourcemap', false);

            JLog::add('Force LESS compilation: '.((bool)$force ? 'true' : 'false'), JLog::DEBUG, $this->logger);
            JLog::add('LESS cache changed: '.((bool)$changed ? 'true' : 'false'), JLog::DEBUG, $this->logger);

            if (!JFile::exists($dest) || $changed || $force) {
                JLog::add('Writing CSS to: '.$dest, JLog::DEBUG, $this->logger);
                JLog::add('Generate CSS sourcemap: '.((bool)$generateSourceMap ? 'true' : 'false'), JLog::DEBUG, $this->logger);

                $options = array('compress'=>true);

                $cssSourceMapUri = str_replace(JPATH_ROOT, JURI::base(), $this->paths->get('css.sourcemap'));

                // Build source map with minified code.
                if ($this->params->get('generate_css_sourcemap', 1)) {
                    $options['sourceMap']         = true;
                    $options['sourceMapWriteTo']  = $this->paths->get('css.sourcemap');
                    $options['sourceMapURL']      = $cssSourceMapUri;
                    $options['sourceMapBasepath'] = JPATH_ROOT;
                } else {
                    JFile::delete($this->paths->get('css.sourcemap'));
                }


                $less = new Less_Parser($options);
                $less->parseFile($this->paths->get('css.less'), JUri::base());

                $css = $less->getCss();
                JFile::write($dest, $css);
                $files = $less->allParsedFiles();

                // update cache.
                $this->updateCache(self::CACHEKEY.'.files.less', $files);
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Minifies all JS code into a single file.
     */
    private function minifyJs()
    {
        $doc = JFactory::getDocument();

        $dest = JPath::clean($this->paths->get('js.minified'));

        if (JFactory::getApplication()->isSite() && $doc instanceof JDocumentHtml) {
            if ($this->getTemplate()) {
                $force = (bool)$this->params->get('force_js_minification', false);
                $changed = (bool)$this->isJsUpdated();

                JLog::add(
                    'Force Javascript minification: '.((bool)$force ? 'true' : 'false'),
                    JLog::DEBUG,
                    $this->logger);
                JLog::add(
                    'Javascript cache changed: '.((bool)$changed ? 'true' : 'false'),
                    JLog::DEBUG,
                    $this->logger);

                if (!JFile::exists($dest) || $changed || $force) {
                    JLog::add('Writing Javascript to: '.$dest, JLog::DEBUG, $this->logger);
                    $uncompressed = '';

                    foreach (array_keys($this->javascripts) as $script) {
                        JLog::add('Minifying: '.$script, JLog::DEBUG, $this->logger);
                        $stream = new JStream();
                        $stream->open($script);
                        $response = $stream->read($stream->filesize());
                        $stream->close();
                        $uncompressed .= $response;
                    }

                    file_put_contents($dest, JSMinPlus::minify($uncompressed, $dest));

                    $this->updateCache(self::CACHEKEY.'.files.js', array_keys($this->javascripts));
                }
            }
        }
    }

    /**
     * Check the cache against the file system to determine whether the LESS
     * files have been updated.
     *
     * @return boolean True if the LESS files have been updated, false
     * otherwise.
     */
    private function isLessUpdated()
    {
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
            $changed = $this->isCacheChanged(self::CACHEKEY.'.files.less');
        }

        return $changed;
    }

    /**
     * Check the cache against the file system to determine whether the javascript
     * files have been updated.
     *
     * @return boolean True if the JS files have been updated, false
     * otherwise.
     */
    private function isJsUpdated()
    {
        $key = self::CACHEKEY.'.files.js';

        if (!($changed = $this->isCacheChanged($key))) {
            // check if we have the same number of js includes.
            if (count($cache = $this->cache->get($key, array())) != count($this->javascripts)) {
                $changed = true;
            }
        }

        return $changed;
    }

    /**
     * Check if the cached files have changed. If files have changed, return true, otherwise return
     * false.
     */
    private function isCacheChanged($key)
    {
        $changed = false;
        $cache = $this->cache->get($key);

        // if there is no cached item, recompile.
        if ($cache && count($cache)) {
            while (!$changed && ($sha1 = current($cache)) !== false) {
                $file = key($cache);

                if (file_exists($file)) {
                    $changed = sha1_file($file) != $sha1;
                } else {
                    $changed = true;
                }

                next($cache);
            }
        } else {
            $changed = true;
        }

        return $changed;
    }

    /**
     * Gets the name of the current template, returning an empty string if the template is in the
     * ignore list.
     */
    private function getTemplate()
    {
        $application = JFactory::getApplication();
        $templates = $this->get('params')->get('ignore_template', array());
        $template = null;

        if (array_search($application->getTemplate(), $templates) === false) {
            $template = $application->getTemplate();
        }

        return $template;
    }

    /**
     * Update the cached files checksums.
     */
    private function updateCache($key, $files)
    {
        // update cache.
        $metadata = $this->cache->get($key, array());

        foreach ($files as $file) {
            $metadata[$file] = sha1_file($file);
        }

        $this->cache->store($metadata, $key);
    }

    /**
     * Register combined Javascript and CSS files for inclusion into the template.
     */
    public function onBeforeRender()
    {
        $doc = JFactory::getDocument();

        if (JFactory::getApplication()->isSite()) {
            if ($this->getTemplate()) {
                $template = $this->getTemplate();
                $templateUrl = JUri::base().'templates/'.$template;

                $doc->addStylesheet($templateUrl.'/css/'.$template.'.min.css');

                $headers = $doc->getHeadData();

                $this->javascripts = JArrayHelper::getValue($headers, 'scripts');

                // all for simple regular expressions for exclude_js_minification parameter.
                $regEx = str_replace("\n", "|", $this->params->get('exclude_js_minification', ''));
                $regEx = implode("|", array_map('trim', explode("|", $regEx)));
                $regEx = str_replace("/", "\/", $regEx);

                foreach (array_keys($this->javascripts) as $script) {
                    if ($regEx && preg_grep("/(".$regEx.")$/", array($script))) {
                        JLog::add('Exclude from minification: '.$script, JLog::DEBUG, $this->logger);
                    } else {
                        unset($headers['scripts'][$script]);

                        $url = new JUri($script);

                        if (!$url->getScheme()) {
                            $url = new JUri(JUri::base());
                            $url->setPath($script);
                        }

                        $key = str_replace(JUri::base(), JPATH_ROOT.'/', (string)$url);

                        $this->javascripts[$key] = $this->javascripts[$script];
                    }

                    unset($this->javascripts[$script]);
                }

                $headers['scripts'][$templateUrl.'/js/jui/'.$template.'.min.js'] = array(
                    'mime'=>'text/javascript',
                    'defer'=>false,
                    'async'=>false);

                $doc->setHeadData($headers);
            }
        }
    }

    /**
     * Compile and minify the CSS and Javascript.
     */
    public function onAfterRender()
    {
        if (JFactory::getApplication()->isSite()) {
            if ($this->getTemplate()) {
                $this->compileCss();
                $this->minifyJs();
            }
        }
    }
}