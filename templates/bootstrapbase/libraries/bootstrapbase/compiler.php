<?php
/**
 * @package     BootstrapBase
 * @subpackage  Compiler
 *
 * @copyright   Copyright (C) 2013-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

abstract class BootstrapBaseCompiler
{
    const CACHEKEY = 'bootstrap';

    protected $cache;

    protected $params;

    public function __construct()
    {
        $app = JFactory::getApplication();
        $this->params = $app->getTemplate(true)->params;

        $this->cache = JFactory::getCache(self::CACHEKEY, '');
        $this->cache->setCaching(true);
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

    public static function run()
    {
        $compiler = new static();
        $compiler->compile();
     
    }
}
