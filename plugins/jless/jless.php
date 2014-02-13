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

/**
 * Compile LESS files on-the-fly.
 * Less compiler lessphp; see http://leafo.net/lessphp/
 */
class PlgSystemJLess extends JPlugin
{
	const LESS_FILE = '/less/template.less';
	const CSS_FILE_UNCOMPRESSED = '/css/template.css';
	const CSS_FILE_COMPRESSED = '/css/template.min.css';
	
	/**
	 * Compile .less files on change
	 */
	function onBeforeRender()
	{
		$document = JFactory::getDocument();
		
		if (JFactory::getApplication()->isSite()) {			
			if (($compiler = $this->get('params')->get('compile', 'gpeasy')) == 'less.js') {
				$this->_compileClientSide();		
			} else {					
				require_once(dirname(__FILE__).'/compilers/'.$compiler.'/lessc.inc.php');
				
				$this->_compileServerSide();
			}
		}
	}
	
	private function _compileClientSide()
	{
		$document = JFactory::getDocument();

		$document->addStyleSheet(JURI::base().'templates/'.$this->_getTemplate().self::LESS_FILE);
		$document->addScript(JURI::base().'media/plg_system_jless/js/less.min.js');

		$this->_deleteCssFiles();
	}
	
	private function _compileServerSide()
	{
		try {
			$this->_compileUncompressed();
			$this->_compileCompressed();
			
			return true;
		} catch (Exception $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			return false;
		}
	}

	/* Produce debug version.
	 */
	private function _compileUncompressed()
	{
		$less = new lessc;
		
		$formatter = new lessc_formatter_classic();
		$formatter->indentChar = "\t";
		$formatter->close = "}\n";
		$formatter->breakSelectors = true;
		$formatter->disableSingle = true;
		
		$less->setFormatter($formatter);
		$less->setPreserveComments(true);

		$this->_compile($less);		
	}

	/* Produce production version.
	 */
	private function _compileCompressed()
	{
		$less = new lessc;
		
		$less->setFormatter(new lessc_formatter_compressed());
			
		$this->_compile($less);
	}
	
	private function _compile($less)
	{
		error_log(get_class($less->getFormatter()));
		if (get_class($less->getFormatter()) == 'lessc_formatter_compressed') {
			$dest = $this->_getCssCompressed();
		} else {
			$dest = $this->_getCssUncompressed();
		}
		
		$src = $this->_getLess();
		
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
	
	private function _deleteCssFiles()
	{
		$array = array();
		$array[] = $this->_getCssUncompressed();
		$array[] = $this->_getCssCompressed();
		
		foreach ($array as $value) {
			if (JFile::exists($value)) {
				JFile::delete($value);
			}
		}
	}
	
	private function _getCssUncompressed()
	{
		$path = JPath::clean(JPATH_ROOT.'/templates/'.$this->_getTemplate());
		return $path.self::CSS_FILE_UNCOMPRESSED;		
	}
	
	private function _getCssCompressed()
	{
		$path = JPath::clean(JPATH_ROOT.'/templates/'.$this->_getTemplate());
		return $path.self::CSS_FILE_COMPRESSED;
	}
	
	private function _getLess()
	{
		$path = JPath::clean(JPATH_ROOT.'/templates/'.$this->_getTemplate());
		return $path.self::LESS_FILE;
	}
	
	private function _getTemplate()
	{
		$application = JFactory::getApplication(); 
		$template = $this->get('params')->get('template', $application->getTemplate());
		
		return $template;
	}
	
	public function onAfterRender()
	{
		// cannot set rel="stylesheet/less for client side compilation using 
		// addStylesheet so need to hack a replace in the document.
		if ($this->get('params')->get('compile') == 'less.js') {
			$body = JFactory::getApplication('site')->getBody();
	
			$body = preg_replace('/(<link rel="stylesheet)(" href=".*.less")/', '$1/less$2', $body);
	
			JFactory::getApplication('site')->setBody($body);
		}
	}
}