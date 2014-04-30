<?php
/**
 * Aide de vue pour extraire un paramètre d'un éventuel tableau
 *
 *
 * @project dafap/DafapLayout
 * @package src/DafapLayout/View/Helper
 * @filesource LayoutParameter.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 24 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LayoutParameter extends AbstractHelper
{

    /**
     * Renvoie le paramètre demandé sa clé est dans le tableau fourni, null sinon
     * 
     * @param string $p
     * @param array $t
     * @throws Exception
     * @return NULL|string|array
     */
    public function __invoke($p, $t)
    {
        if (!is_string($p)) {
            ob_start();
            var_dump($p);
            $mess = sprintf("Le paramètre demandé doit être une chaine de caractère. On a reçu %s", ob_get_clean());
            throw new Exception($mess, 1);
        }
        if (empty($t)) {
            return null;
        } elseif (! is_array($t)) {
            ob_start();
            var_dump($t);
            $mess = sprintf("Mauvaise configuration des paramètres. Un tableau est attendu. On a reçu %s", ob_get_clean());
            throw new Exception($mess, 2);
        }
        if (array_key_exists($p, $t)) {
            return $t[$p];
        } else {
            return null;
        }
    }
}