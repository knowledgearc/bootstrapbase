<?php
/**
 * @package     BootstrapBase
 * @subpackage  Compiler
 *
 * @copyright   Copyright (C) 2013-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('BootstrapBaseCompiler', dirname(__FILE__).'/../compiler.php');
JLoader::register('JsMinPlus', dirname(__FILE__).'/jsminplus.php');

class BootstrapBaseCompilerJs extends BootstrapBaseCompiler
{
    private $paths;

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        JLog::addLogger(array());
        $this->logger = 'bootstrapbase';

        $this->paths = new JRegistry;

        $template = $app->getTemplate();
        $jui = JPATH_THEMES.'/'.$template.'/js/jui/';

        $this->paths->set('js.compressed', $jui.$template.'.min.js');
        $this->paths->set('js.uncompressed',
            array(
                $jui.'jquery.min.js',
                JPATH_ROOT.'/media/jui/js/jquery-noconflict.js',
                JPATH_ROOT.'/media/jui/js/jquery-migrate.min.js',
                $jui.'bootstrap.min.js',
                $jui.'tooltip.min.js'
            ));
    }

    public function compile()
    {
        $doc = JFactory::getDocument();

        $headers = $doc->getHeadData();

        $javascripts = JArrayHelper::getValue($headers, 'scripts');

        $dest = JPath::clean($this->paths->get('js.compressed'));

        $compile = $this->params->get('minify_js', 1);
        $changed = (bool)$this->isJsUpdated();

        JLog::add(
            'Javascript cache changed: '.((bool)$changed ? 'true' : 'false'),
            JLog::DEBUG,
            $this->logger);

        $force = (bool)($compile == 2);
        $changed = (bool)($compile == 1 && $changed);

        JLog::add(
            'Force Javascript minification: '.((bool)$force ? 'true' : 'false'),
            JLog::DEBUG,
            $this->logger);

        JLog::add(
            'Minify Javascript: '.((bool)$changed ? 'true' : 'false'),
            JLog::DEBUG,
            $this->logger);

        $uncompressed = '';

        foreach (array_keys($javascripts) as $script) {
            $url = new JUri($script);

            if (!$url->getScheme()) {
                $url = new JUri(JUri::base());
                $url->setPath($script);
            }

            $key = str_replace(JUri::base(), JPATH_ROOT.'/', (string)$url);

            if (array_search($key, $this->paths->get('js.uncompressed')) !== false) {
                unset($headers['scripts'][$script]);

                if (!JFile::exists($dest) || $changed || $force) {
                    JLog::add('Compressing: '.$key.' to '.$dest, JLog::DEBUG, $this->logger);

                    $stream = new JStream();
                    $stream->open($key);
                    $response = $stream->read($stream->filesize());
                    $stream->close();
                    $uncompressed .= $response;
                }
            }
        }

        if ($uncompressed) {
            file_put_contents($dest, JSMinPlus::minify($uncompressed, $dest));
            $this->updateCache(self::CACHEKEY.'.files.js', $this->paths->get('js.uncompressed'));
        }

        // workaround. There needs to be at least one script.
        if (count($headers['scripts']) == 0) {
            $url = str_replace(JPATH_ROOT.'/', JUri::base(), $dest);
            $headers['scripts'][$url] = array(
                'mime'=>'text/javascript',
                'defer'=>false,
                'async'=>false);
        }

        $doc->setHeadData($headers);
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
        return $this->isCacheChanged(self::CACHEKEY.'.files.js');
    }
}
