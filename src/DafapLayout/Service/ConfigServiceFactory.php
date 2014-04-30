<?php
/**
 * ServiceFactory qui renvoie le tableau 'layout_manager' de la configuration
 *
 *
 * @project dafap/DafapLayout
 * @package src/DafapLayout/Service
 * @filesource ConfigServiceFactory.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 27 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayObject;

class ConfigServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return $config['layout_manager'];
    }
}
