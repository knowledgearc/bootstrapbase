<?php

/**
 * @package     SCss
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2013-2017 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::import('joomla.filesystem.stream');
JLoader::import('joomla.filesystem.folder');
JLoader::import('joomla.log.log');

JLoader::registerNamespace(
    'Leafo\ScssPhp',
    __DIR__.'/vendor/leafo/scssphp/src',
    false,
    false,
    'psr4');

use Leafo\ScssPhp\Compiler;

class PlgSystemJScss extends JPlugin {

    const LOGGER = 'jscss';

    const CACHEKEY = 'scss';

    private $paths;

    private $compiler;

    private $frequency;

    private $formatting;

    protected $cache;

    public function __construct(&$subject, $config) {
        $this->autoloadLanguage = true;
        parent::__construct($subject, $config);

        JLog::addLogger(array());

        $app = JFactory::getApplication();

        $this->cache = JFactory::getCache(self::CACHEKEY, '');
        $this->cache->setCaching(true);

        $this->frequency = $this->params->get('compile_frequency', '1');
        $this->formatting = $this->params->get("output_formatting", "crunched");

        $this->paths = new JRegistry;

        $templatePath = JPATH_THEMES.'/'.$app->getTemplate();
        $template = $app->getTemplate();

        $in = $templatePath.'/scss/template.scss';
        $out = $templatePath.'/css/'.$template.'.css';

        $this->paths->set('css.in', $in);
        $this->paths->set('css.out', $out);
        $this->paths->set('css.sourcemap', $out.'.map');
    }

    /**
     * Compiles the CSS source code into a single CSS file.
     */
    public function onAfterInitialise()
    {
        if (JFactory::getApplication()->isClient('administrator')) {
            return true;
        }

        if (!JFile::exists($this->paths->get('css.in'))) {
            JLog::add("No template.scss file found at ".$this->paths->get('css.in'), JLog::WARNING, self::LOGGER);

            return true;
        }

        try {
            $dest = $this->paths->get('css.out');
            $compile = $this->frequency;
            $changed = (bool)$this->isCssUpdated();

            JDEBUG ? JLog::add('CSS cache changed: '.((bool)$changed ? 'true' : 'false'), JLog::DEBUG, self::LOGGER) : null;

            $changed = (bool)(($compile == 2) || ($compile == 1 && $changed));

            $doCompile = (!JFile::exists($dest) || $changed);

            JDEBUG ? JLog::add('Force CSS compilation: '.($compile == 2 ? 'true' : 'false'), JLog::DEBUG, self::LOGGER) : null;
            JDEBUG ? JLog::add('CSS file exists: '.((bool)JFile::exists($dest) ? 'true' : 'false'), JLog::DEBUG, self::LOGGER) : null;
            JDEBUG ? JLog::add('Compiling CSS: '.((bool)$doCompile ? 'true' : 'false'), JLog::DEBUG, self::LOGGER) : null;

            if ($doCompile) {
                $formatter = "Leafo\ScssPhp\Formatter\\".ucfirst($this->formatting);
                $this->cache->store($this->formatting, self::CACHEKEY.'.scss.formatter');

                $scss = new Compiler();
                $scss->setFormatter($formatter);
                $css = $scss->compile('@import "'.$this->paths->get('css.in').'";');

                $files = array_keys($scss->getParsedFiles());

                JDEBUG ? JLog::add('Writing CSS to: '.$dest, JLog::DEBUG, self::LOGGER) : null;

                JFile::write($dest, $css);

                // update cache.
                $this->updateCache(self::CACHEKEY.'.files.css', $files);
                $this->cache->store('scss', self::CACHEKEY.'.compiler');
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

        if (!$changed) {
            if ($this->cache->get(self::CACHEKEY.'.scss.formatter') !== $this->formatting) {
                $changed = true;
            }
        }

        if (!$changed) {
            $changed = $this->isCacheChanged(self::CACHEKEY.'.files.css');
        }

        return $changed;
    }


    /**
     * Check if the cached files have changed. If files have changed, return true, otherwise return
     * false.
     */
    public function isCacheChanged($key)
    {
        $changed = false;
        $cache = $this->cache->get($key);

        // if there is no cached item, recompile.
        if ($cache && count($cache)) {
            while (!$changed && ($sha1 = current($cache)) !== false) {
                $file = key($cache);

                if (JFile::exists($file)) {
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
     * Update the cached files checksums.
     */
    public function updateCache($key, $files)
    {
        // update cache.
        $metadata = $this->cache->get($key, array());

        foreach ($files as $file) {
            $metadata[$file] = sha1_file($file);
        }

        $this->cache->store($metadata, $key);
    }
}
