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

jimport('joomla.log.log');

/**
 * Compile LESS files on-the-fly.
 * Less compiler less.php; see http://github.com/oyejorge/less.php
 */
class PlgSystemBootstrapbase extends JPlugin
{
    const LESS_FILE = '/less/template.less';
    const CACHEKEY = 'file.metadata';

    private $paths;

    public function __construct($subject, $config = array())
    {
        parent::__construct($subject, $config);

        $this->paths = new JRegistry;

        $templatePath = JPATH_THEMES.'/'.$this->_getTemplate();
        $this->paths->set('css.less', $templatePath.self::LESS_FILE);
        $this->paths->set('css.compressed', $templatePath.'/css/'.$this->_getTemplate().'.min.css');
        $this->paths->set('css.uncompressed', $templatePath.'/css/'.$this->_getTemplate().'.css');
        $this->paths->set('css.sourcemap', $templatePath.'/css/'.$this->_getTemplate().'.css.map');

        $this->_cache = JFactory::getCache('bootstrapbase', '');
        $this->_cache->setCaching(true);
    }

    private function _compileCss()
    {
        try {
            JLoader::registerNamespace('Less', __DIR__.'/compilers/oyejorge/less.php/lib');

            $force = (bool)$this->params->get('force', false);

            if (!JFile::exists($this->paths->get('css.compressed')) || $this->_isLessUpdated() || $force) {
                $options = array('compress'=>true);

                $cssSourceMapUri = str_replace(JPATH_ROOT, JURI::base(), $this->paths->get('css.sourcemap'));

                // Build source map with minified code.
                if ($this->params->get('generate_css_sourcemap', 1)) {
                    $options['sourceMap']         = true;
                    $options['sourceMapWriteTo']  = $this->paths->get('css.sourcemap');
                    $options['sourceMapURL']      = $cssSourceMapUri;
                    $options['sourceMapBasepath'] = JPATH_ROOT;
                } else {
                    JFile::delete($this->cssSourceMap);
                }


                $less = new Less_Parser($options);
                $less->parseFile($this->paths->get('css.less'), JUri::base());

                $css = $less->getCss();
                JFile::write($this->paths->get('css.compressed'), $css);
                $files = $less->allParsedFiles();

                // Generate uncompressed css.
                if ($this->params->get('generate_css_uncompressed', 0)) {
                    $less = new Less_Parser();
                    $less->parseFile($src, '/');

                    JFile::write($this->paths->get('css.uncompressed'), $less->getCss());
                } else {
                    JFile::delete($this->paths->get('css.uncompressed'));
                }

                // update cache.
                $metadata = array();

                foreach ($files as $file) {
                    $metadata[$file] = sha1_file($file);
                }

                $this->_cache->store($metadata, self::CACHEKEY);
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Check the cache against the file system to determine whether the LESS
     * files have been updated.
     *
     * @return boolean True if the LESS files have been updated, false
     * otherwise.
     */
    private function _isLessUpdated()
    {
        $changed = false;

        // if there is no cached item, recompile.
        if (!is_array($files = $this->_cache->get(self::CACHEKEY)))
        {
            $changed = true;
        }

        $generateSourceMap = $this->params->get('generate_css_sourcemap', false);

        // if the source map still exists but shouldn't be created, just recompile.
        if (JFile::exists($this->paths->get('css.sourcemap')) && !$generateSourceMap) {
            $changed = true;
        }

        // if the source map doesn't exist but should, just recompile.
        if (!JFile::exists($this->paths->get('css.sourcemap')) && $generateSourceMap) {
            $changed = true;
        }

        while (!$changed && ($sha1 = current($files)) !== false) {
            $file = key($files);

            if (file_exists($file)) {
                $changed = sha1($file) != $sha1;
            } else {
                $changed = true;
            }

            next($files);
        }

        return $changed;
    }

    private function _getTemplate()
    {
        $application = JFactory::getApplication();
        $templates = $this->get('params')->get('ignore_template', array());
        $template = null;

        if (array_search($application->getTemplate(), $templates) === false) {
            $template = $application->getTemplate();
        }

        return $template;
    }

    public function onAfterRender()
    {
        $document = JFactory::getDocument();

        if (JFactory::getApplication()->isSite()) {
            if ($this->_getTemplate()) {
                $this->_compileCss();
            }
        }
    }
}