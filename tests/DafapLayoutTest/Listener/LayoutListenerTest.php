<?php
/**
 * Test pour le listener LayoutListener
 *
 *
 * @project dafap/DafapLayout
 * @package tests/DafapLayoutTest/Listener
 * @filesource LayoutListener
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 29 avr. 2014
 * @version 2014-1
 */
namespace DafapLayoutTest\Listener;

use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\Controller\AbstractActionController;
use DafapLayout\Listener\LayoutListener;

class LayoutListenerTest extends PHPUnit_Framework_TestCase
{

    protected $listener;

    protected $controller;

    public function setUp()
    {
        $this->listener = new LayoutListener();
        $this->controller = new IndexController();
    }

    public function testClassController()
    {
        $this->assertEquals('DafapLayoutTest\Listener\IndexController', get_class($this->controller), 'Le nom de la classe du controller n\'est pas bon.');
    }

    /**
     * Vérifie si le listener est monté avec la bonne méthode callback et la bonne priority
     */
    public function testAttachesRendererAtExpectedPriority()
    {
        $event = new EventManager();
        $event->attachAggregate($this->listener);
        $listeners = $event->getListeners(MvcEvent::EVENT_DISPATCH);
        
        $expectedCallback = array(
            $this->listener,
            'onDispatch'
        );
        $expectedPriority = - 9400;
        
        $found = false;
        foreach ($listeners as $listener) {
            $callback = $listener->getCallback();
            if ($callback == $expectedCallback) {
                if ($listener->getMetadatum('priority') == $expectedPriority) {
                    $found = true;
                    break;
                }
            }
        }
        $this->assertTrue($found, 'LayoutListener mal monté.');
    }

    /**
     * Vérifie qu'on peut détacher le listener
     */
    public function testCanDetachListenersFromEventManager()
    {
        $events = new EventManager();
        $events->attachAggregate($this->listener);
        $this->assertEquals(1, count($events->getListeners(MvcEvent::EVENT_DISPATCH)));
        
        $events->detachAggregate($this->listener);
        $this->assertEquals(0, count($events->getListeners(MvcEvent::EVENT_DISPATCH)));
    }

    public function testOnDispatchCaseDefaultsLayout()
    {
        $config = array(
            'layout_map' => array(
                'defaults' => 'layout/defaults'
            ),
            'parameter' => array(
                'layout/defaults' => array(
                    'var' => 'value'
                )
            )
        );
        $sm = new ServiceManager(array(
            'DafapLayout\Config' => $config
        ));
        
        $mockApplication = new Application();
        $mockApplication->setServiceManager($sm);
        $target = new IndexController();
        $event = new MvcEvent();
        $event->setApplication($mockApplication);
        $event->setTarget($target);
        
        $this->listener->onDispatch($event);
        
        $this->assertEquals($config['layout_map']['defaults'], $target->layout()
            ->getTemplate(), 'Le layout n\'a pas été mis en place.');
        $layout = $target->layout();
        $this->assertEquals($config['parameter']['layout/defaults'], $layout->parameter, 'Pas de propriété parameter ou la propriété ne contient pas ce qu\'il faut.');
    }

    public function testOnDispatchCaseModuleMatchLayout()
    {
        $config = array(
            'layout_map' => array(
                'DafapLayoutTest' => 'layout/test'
            ),
            'parameter' => array(
                'layout/test' => array(
                    'var' => 'value'
                )
            )
        );
        $sm = new ServiceManager(array(
            'DafapLayout\Config' => $config
        ));
        
        $mockApplication = new Application();
        $mockApplication->setServiceManager($sm);
        $target = new IndexController();
        $event = new MvcEvent();
        $event->setApplication($mockApplication);
        $event->setTarget($target);
        
        $this->listener->onDispatch($event);
        
        $this->assertEquals($config['layout_map']['DafapLayoutTest'], $target->layout()
            ->getTemplate(), 'Le layout n\'a pas été mis en place.');
        $layout = $target->layout();
        $this->assertEquals($config['parameter']['layout/test'], $layout->parameter, 'Pas de propriété parameter ou la propriété ne contient pas ce qu\'il faut.');
    }

    public function testOnDispatchCaseControllerMatchLayout()
    {
        $config = array(
            'layout_map' => array(
                'DafapLayoutTest\Listener\IndexController' => 'layout/test'
            ),
            'parameter' => array(
                'layout/test' => array(
                    'var' => 'value'
                )
            )
        );
        $sm = new ServiceManager(array(
            'DafapLayout\Config' => $config
        ));
        
        $mockApplication = new Application();
        $mockApplication->setServiceManager($sm);
        $target = new IndexController();
        $event = new MvcEvent();
        $event->setApplication($mockApplication);
        $event->setTarget($target);
        
        $this->listener->onDispatch($event);
        
        $this->assertEquals($config['layout_map']['DafapLayoutTest\Listener\IndexController'], $target->layout()
            ->getTemplate(), 'Le layout n\'a pas été mis en place.');
        $layout = $target->layout();
        $this->assertEquals($config['parameter']['layout/test'], $layout->parameter, 'Pas de propriété parameter ou la propriété ne contient pas ce qu\'il faut.');
    }

    public function testOnDispatchCaseControllerNotMatchLayout()
    {
        $config = array(
            'layout_map' => array(
                'defaults' => 'layout/defaults',
                'DafapLayoutTest\Listener\TestController' => 'layout/test'
            )
            ,
            'parameter' => array(
                'layout/defaults' => array(
                    'foo' => 'bar'
                ),
                'layout/test' => array(
                    'foo' => 'baz'
                )
            )
        );
        $sm = new ServiceManager(array(
            'DafapLayout\Config' => $config
        ));
        
        $mockApplication = new Application();
        $mockApplication->setServiceManager($sm);
        $target = new IndexController();
        $event = new MvcEvent();
        $event->setApplication($mockApplication);
        $event->setTarget($target);
        
        $this->listener->onDispatch($event);
        
        $this->assertEquals($config['layout_map']['defaults'], $target->layout()
            ->getTemplate(), 'Le layout n\'a pas été mis en place.');
        $layout = $target->layout();
        $this->assertEquals($config['parameter']['layout/defaults'], $layout->parameter, 'Pas de propriété parameter ou la propriété ne contient pas ce qu\'il faut.');
    }
}

/**
 * Les classes ci-dessous simulent ServiceManager::get(), Application::getServiceManager() et définissent un contrôleur
 * 
 * @author A. Pomirol (Dafap informatique)
 *        
 *         Simulation d'un ServiceManager
 */
class ServiceManager
{

    private $container;

    public function __construct($config)
    {
        $this->container = $config;
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->container)) {
            return $this->container[$key];
        } else {
            return null;
        }
    }
}

/**
 * Simulation d'une Application
 * 
 * @author admin
 *        
 */
class Application implements ApplicationInterface
{

    public $events;

    public $request;

    public $response;

    public $serviceManager;

    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
    }

    public function getEventManager()
    {
        return $this->events;
    }

    /**
     * Get the locator object
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Get the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Run the application
     *
     * @return \Zend\Http\Response
     */
    public function run()
    {
        return $this->response;
    }

    public function bootstrap()
    {
        $event = new MvcEvent();
        $event->setApplication($this);
        $event->setTarget($this);
        $this->getEventManager()->trigger(MvcEvent::EVENT_BOOTSTRAP, $event);
    }
}

/**
 * Définition d'un controller pour ces tests
 * 
 * @author admin
 *        
 */
class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return;
    }
}