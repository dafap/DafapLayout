<?php
/**
 * Test pour le service ConfigServiceFactory
 *
 *
 * @project dafap/DafapLayout
 * @package tests/DafapLayoutTest/Service
 * @filesource ConfigServiceFactory.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 29 avr. 2014
 * @version 2014-1
 */
namespace DafapLayoutTest\Service;

use PHPUnit_Framework_TestCase;
use DafapLayout\Service\ConfigServiceFactory;

/**
 * Test pour {@see \DafapLayout\Service\ConfigServiceFactory}
 *
 * @author Alain Pomirol <pomirol@gmail.com>
 */
class ConfigServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \DafapLayout\Service\ConfigServiceFactory::createService
     */
    public function testCreateService()
    {
        $factory        = new ConfigServiceFactory();
        $serviceLocator = $this->getMock('Zend\\ServiceManager\\ServiceLocatorInterface');

        $serviceLocator
        ->expects($this->any())
        ->method('get')
        ->will($this->returnValue(
            array(
                'layout_manager' => array('layout_map' => array(), 'parameter' => array())
            )
        ));

        $this->assertSame(array('layout_map' => array(), 'parameter' => array()), $factory->createService($serviceLocator));
    }
}

