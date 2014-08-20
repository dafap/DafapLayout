<?php
/**
 * Aide de vue pour mettre en place les HeadScripts
 *
 *
 * @project dafap/DafapLayout
 * @package src/DafapLayout/View/Helper
 * @filesource HeadScripts.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 22 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadScript;

class HeadScripts extends AbstractHelper
{

    public function __invoke($base_path, $jsFilesArray = array(), $scripts = array())
    {
        $head_script = new HeadScript();
        foreach ((array) $jsFilesArray as $js_file) {
            if (is_string($js_file)) {
                $head_script = $head_script->prependFile($this->concatPath($base_path, $js_file));
            } elseif (is_array($js_file)) {
                $src = $this->getParam('src', $js_file);
                $type = $this->getParam('type', $js_file);
                $attrs = $this->getParam('attrs', $js_file);
                if (! is_null($src)) {
                    $head_script = $head_script->prependFile($this->concatPath($base_path, $src), $type, $attrs);
                }
            } else {
                throw new Exception('Erreur de structure pour la définition des fichiers css.');
            }
        }
        foreach ((array) $scripts as $js_script) {
            if (is_string($js_script)) {
                $head_script = $head_script->appendScript();
            } elseif (is_array($js_script)) {
                $template = $this->getParam('template', $js_script);
                $type = $this->getParam('type', $js_script);
                $attrs = $this->getParam('attrs', $js_script);
                if (! is_null($template)) {
                    $head_script = $head_script->appendScript($template, $type, $attrs);
                }
            } else {
                throw new Exception('Erreur de structure pour la définition des JavaScripts');
            }
        }
        return $head_script;
    }

    private function getParam($nom, $tableau)
    {
        if (array_key_exists($nom, $tableau)) {
            return $tableau[$nom];
        } else {
            return null;
        }
    }

    /**
     * Si $file commence par 2 / (//) alors on ne concatène pas car c'est une adresse absolue (url)
     *
     * @param string $base_path            
     * @param string $file            
     * @return string
     */
    private function concatPath($base_path, $file = null)
    {
        if (! is_null($file)) {
            if (substr($file, 0, 2) == '//') {
                return $file;
            }
            $file = '/' . ltrim($file, '/');
        }
        $base_path = rtrim(str_replace('\\', '/', $base_path), '/');
        
        return $base_path . $file;
    }
}
