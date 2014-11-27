<?php
/**
 * @package     Bootstrapbase
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2014 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument Javascript renderer.
 *
 * Provides better performance and adherence to standards by including the javascript just
 * before the closing body tag.
 *
 * @package     Bootstrapbase
 * @subpackage  Document
 */
class JDocumentRendererJs extends JDocumentRenderer
{
    /**
        * Renders the document javascript and returns the results as a string
        *
        * @param   string  $js       (unused)
        * @param   array   $params   Associative array of values
        * @param   string  $content  The script
        *
        * @return  string  The output of the script
        *
        * @since   11.1
        *
        * @note    Unused arguments are retained to preserve backward compatibility.
        */
    public function render($js, $params = array(), $content = null)
    {
            return $this->fetchJs($this->_doc);
    }

    /**
        * Generates the javascript HTML and return the results as a string
        *
        * @param   JDocument  $document  The document for which the javascript will be created
        *
        * @return  string  The javascript hTML
        *
        * @since   11.1
        */
    public function fetchJs($document)
    {
        // Get line endings
        $lnEnd = $document->_getLineEnd();
        $tab = $document->_getTab();
        $buffer = '';

        // Generate script file links
        foreach ($document->_scripts as $strSrc => $strAttr)
        {
            $buffer .= $tab . '<script src="' . $strSrc . '"';
            $defaultMimes = array(
                'text/javascript', 'application/javascript', 'text/x-javascript', 'application/x-javascript'
            );

            if (!is_null($strAttr['mime']) && (!$document->isHtml5() || !in_array($strAttr['mime'], $defaultMimes)))
            {
                $buffer .= ' type="' . $strAttr['mime'] . '"';
            }

            if ($strAttr['defer'])
            {
                $buffer .= ' defer="defer"';
            }

            if ($strAttr['async'])
            {
                $buffer .= ' async="async"';
            }

            $buffer .= '></script>' . $lnEnd;
        }

        // Generate script declarations
        foreach ($document->_script as $type => $content)
        {
            $buffer .= $tab . '<script type="' . $type . '">' . $lnEnd;

            // This is for full XHTML support.
            if ($document->_mime != 'text/html')
            {
                $buffer .= $tab . $tab . '<![CDATA[' . $lnEnd;
            }

            $buffer .= $content . $lnEnd;

            // See above note
            if ($document->_mime != 'text/html')
            {
                $buffer .= $tab . $tab . ']]>' . $lnEnd;
            }
            $buffer .= $tab . '</script>' . $lnEnd;
        }

        // Generate script language declarations.
        if (count(JText::script()))
        {
            $buffer .= $tab . '<script type="text/javascript">' . $lnEnd;
            $buffer .= $tab . $tab . '(function() {' . $lnEnd;
            $buffer .= $tab . $tab . $tab . 'var strings = ' . json_encode(JText::script()) . ';' . $lnEnd;
            $buffer .= $tab . $tab . $tab . 'if (typeof Joomla == \'undefined\') {' . $lnEnd;
            $buffer .= $tab . $tab . $tab . $tab . 'Joomla = {};' . $lnEnd;
            $buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText = strings;' . $lnEnd;
            $buffer .= $tab . $tab . $tab . '}' . $lnEnd;
            $buffer .= $tab . $tab . $tab . 'else {' . $lnEnd;
            $buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText.load(strings);' . $lnEnd;
            $buffer .= $tab . $tab . $tab . '}' . $lnEnd;
            $buffer .= $tab . $tab . '})();' . $lnEnd;
            $buffer .= $tab . '</script>' . $lnEnd;
        }

        foreach ($document->_custom as $custom)
        {
            $buffer .= $tab . $custom . $lnEnd;
        }

        return $buffer;
    }
}
