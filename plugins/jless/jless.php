<?php
/**
 * @package   System Plugin - automatic Less compiler - for Joomla 2.5 and 3.x
 * @version   0.7.2 Beta
 * @author    Andreas Tasch
 * @copyright (C) 2012-2013 - Andreas Tasch
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

// no direct access
defined('_JEXEC') or die();

if (!class_exists('lessc'))
{
	require_once('lessc.php');
}

/**
 * Plugin checks and compiles updated .less files on page load. No need to manually compile your .less files again.
 * Less compiler lessphp; see http://leafo.net/lessphp/
 */
class PlgSystemJLess extends JPlugin
{
	/**
	 * Compile .less files on change
	 */
	function onBeforeRender()
	{
		if (JFactory::getApplication()->isSite()) {
			if ($template = $this->get('params')->get('template')) {
				$application = JFactory::getApplication();
				$path = JPath::clean(JPATH_ROOT.'/templates/'.$template.'/');
				$src = $path.'/less/template.less';
				$dest = $path.'/css/template.css';
				$destCompressed = $path.'/css/template.min.css';				
	
				try {
					// Produce debug version.
					$less = new lessc;
					
					$formatter = new lessc_formatter_classic();
					$formatter->indentChar = "\t";
					$formatter->close = "}\n";
					$formatter->breakSelectors = true;
					$formatter->disableSingle = true;
					
					$less->setFormatter($formatter);
					$less->setPreserveComments(true);
					$less->compileFile($src, $dest);
					
					// Produce production version.
					$less = new lessc;

					$less->setFormatter(new lessc_formatter_compressed());
					$less->compileFile($src, $destCompressed);
					return true;
				} catch (Exception $e) {
					$application->enqueueMessage($e->getMessage(), 'error');
					return false;
				}
			}
		}
	}
}