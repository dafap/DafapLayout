<?php
/**
 * Listener permetant de traiter le layout des pages qui ont le status 404 (not found) ou 500 (exception)
 *
 *
 * @project dafap/DafapLayout
 * @package src/DafapLayout/Listener
 * @filesource LayoutErrorListener.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 27 avr. 2014
 * @version 2014-1
 */
namespace DafapLayout\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class LayoutErrorListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array(
            $this,
            'onRender'
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
     * MvcEvent::EVENT_RENDER event callback
     *
     * @param MvcEvent $event            
     */
    public function onRender(MvcEvent $event)
    {
        $statusCode = $event->getResponse()->getStatusCode();
        $config = $event->getApplication()->getServiceManager()->get('DafapLayout\Config');
        if ($statusCode == 404 || $statusCode == 500) {
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('layout/error');
            $viewModel->setVariable('parameter', $config['parameter']['layout/error']);     
        }
    }
    
}

