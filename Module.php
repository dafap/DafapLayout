<?php
/**
 * Module ManageLayout
 *  - mise en place du layout et de ses variables (en fonction du module du controleur appelé)
 *  - définition d'aides de vue pour les layouts
 *  - la configuration des layouts et des variables se fait dans un fichier config/autoload/manage-layout.global.php
 *    (copier le modèle de config/manage-layout.global.php.dist dans le config/autoload de l'application puis l'adapter)
 *
 * @project dafap/DafapLayout
 * @package /
 * @filesource Module.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 24 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;

class Module implements 
BootstrapListenerInterface
{
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function onBootstrap(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager->attach($serviceManager->get('DafapLayout\LayoutListener'));
        $eventManager->attach($serviceManager->get('DafapLayout\LayoutErrorListener'));
    }
}

