<?php
/**
 * Aide de vue pour mettre en place les HeadLink
 *
 *
 * @project dafap/DafapLayout
 * @package src/DafapLayout/View/Helper
 * @filesource HeadLinks.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 22 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadLink;

class HeadLinks extends AbstractHelper
{

    public function __invoke($base_path, $favicon_file = null, $cssFilesArray = array())
    {
        $head_link = new HeadLink();
        if (! is_null($favicon_file) && is_string($favicon_file)) {
            $head_link = $head_link(array(
                'rel' => 'shortcut icon',
                'type' => 'image/vnd.microsoft.icon',
                'href' => $this->concatPath($base_path, $favicon_file)
            ));
        } else {
            $head_link = $head_link();
        }
        foreach ((array) $cssFilesArray as $css_file) {
            if (is_string($css_file)) {
                $head_link = $head_link->prependStylesheet($this->concatPath($base_path, $css_file));
            } elseif (is_array($css_file)) {
                $href = $this->getParam('href', $css_file);
                $media = $this->getParam('media', $css_file) ?: 'all';
                $conditionalStylesheet = $this->getParam('conditionalStylesheet', $css_file) ?: true;
                $extras = $this->getParam('extras', $css_file) ?: array();
                if (!is_null($href)) {
                    $head_link = $head_link->prependStylesheet($this->concatPath($base_path, $href), $media, $conditionalStylesheet, $extras);
                }
            } else {
                throw new Exception('Erreur de structure pour la définition des fichiers css.');
            }
        }
        return $head_link;
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
