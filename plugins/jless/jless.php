<?php
/**
 * @package     BootstrapBase.Plugin
 * @subpackage  System
 *
 * @copyright   Copyright (C) 2013-2014 KnowledgeARC Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

jimport('joomla.log.log');

if (!class_exists('lessc'))
{
	require_once('lessc.php');
}

/**
 * Compile LESS files on-the-fly.
 * Less compiler lessphp; see http://leafo.net/lessphp/
 */
class PlgSystemJLess extends JPlugin
{
	/**
	 * Compile .less files on change
	 */
	function onBeforeRender()
	{
		$document = JFactory::getDocument();
		
		if (JFactory::getApplication()->isSite()) {
			if ($template = $this->get('params')->get('template')) {
				if ($this->get('params')->get('compile', 0) == 1) {
					$this->_compileClientSide($template);
				} else {
					$this->_compileServerSide($template);
				}
			}
		}
	}
	
	private function _compileClientSide($template)
	{
		$document = JFactory::getDocument();
		
		$document->addStyleSheet(JURI::base().'templates/'.$template.'/less/template.less');
		$document->addScript(JURI::base().'media/jless/js/less.min.js');
	}
	
	private function _compileServerSide($template)
	{
		$application = JFactory::getApplication();
		
		$path = JPath::clean(JPATH_ROOT.'/templates/'.$template.'/');
		$src = $path.'/less/template.less';
		$dest = $path.'/css/template.css';
		$destCompressed = $path.'/css/template.min.css';
		
		try {
			$this->_compileUncompressed($src, $dest);
			$this->_compileCompressed($src, $destCompressed);
			
			return true;
		} catch (Exception $e) {
			$application->enqueueMessage($e->getMessage(), 'error');
			return false;
		}
	}

	/* Produce debug version.
	 */
	private function _compileUncompressed($src, $dest)
	{
		$less = new lessc;
		
		$formatter = new lessc_formatter_classic();
		$formatter->indentChar = "\t";
		$formatter->close = "}\n";
		$formatter->breakSelectors = true;
		$formatter->disableSingle = true;
		
		$less->setFormatter($formatter);
		$less->setPreserveComments(true);

		$this->_compile($less, $src, $dest);		
	}

	/* Produce production version.
	 */
	private function _compileCompressed($src, $dest)
	{
		$less = new lessc;
		
		$less->setFormatter(new lessc_formatter_compressed());
			
		$this->_compile($less, $src, $dest);
	}
	
	private function _compile($less, $src, $dest)
	{
		$cache = JFactory::getCache('jless', '');
		$cache->setCaching(true);
			
		if ($current = $cache->get($src)) {
			$src = $current;
		}
			
		$new = $less->cachedCompile($src);
			
		$currentUpdated = JArrayHelper::getValue($current, "updated");
		$newUpdated = JArrayHelper::getValue($new, "updated");
		
		if (!JFile::exists($dest) || !$current || $newUpdated > $currentUpdated) {
			$cache->store($new, $src);
			JFile::write($dest, JArrayHelper::getValue($new, "compiled"));
		}	
	}
	
	public function onAfterRender()
	{
		// cannot set rel="stylesheet/less for client side compilation using 
		// addStylesheet so need to hack a replace in the document.
		if ($this->get('params')->get('compile') == 1) {
			$body = JFactory::getApplication('site')->getBody();
	
			$body = preg_replace('/(<link rel="stylesheet)(" href=".*.less")/', '$1/less$2', $body);
	
			JFactory::getApplication('site')->setBody($body);
		}
	}
}