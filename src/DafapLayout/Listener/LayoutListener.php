<?php
/**
 * Listener mettant en place le layout des pages à partir de la configuration layout_manager
 *
 *
 * @project dafap/DafapLayout
 * @package src/DafapLayout/Listener
 * @filesource LayoutListener.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 29 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class LayoutListener implements ListenerAggregateInterface
{

    /**
     *
     * @var array
     */
    protected $listeners = array();
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'onDispatch'
        ), - 9400);
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\ListenerAggregateInterface::detach()
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * MvcEvent::EVENT_DISPATCH event callback
     *
     * @param MvcEvent $event            
     */
    public function onDispatch(MvcEvent $event)
    {
        $config = $event->getApplication()->getServiceManager()->get('DafapLayout\Config');
        if (empty($config)) return;
        $controller = $event->getTarget();
        if (! $controller) {
            $controller = $event->getRouteMatch()->getParam('controller', '');
        }
        $controller_class = get_class($controller);
        $module_namespace = substr($controller_class, 0, strpos($controller_class, '\\'));
        if (array_key_exists($controller_class, $config['layout_map'])) {
            $controller->layout($config['layout_map'][$controller_class]);
            if (array_key_exists($config['layout_map'][$controller_class], $config['parameter'])) {
                $controller->layout()->parameter = $config['parameter'][$config['layout_map'][$controller_class]];
            }
        } elseif (array_key_exists($module_namespace, $config['layout_map'])) {
            $controller->layout($config['layout_map'][$module_namespace]);
            if (array_key_exists($config['layout_map'][$module_namespace], $config['parameter'])) {
                $controller->layout()->parameter = $config['parameter'][$config['layout_map'][$module_namespace]];
            }
        } else {
            $controller->layout($config['layout_map']['defaults']);
            if (array_key_exists($config['layout_map']['defaults'], $config['parameter'])) {
                $controller->layout()->parameter = $config['parameter'][$config['layout_map']['defaults']];
            }
        }
    }
}
