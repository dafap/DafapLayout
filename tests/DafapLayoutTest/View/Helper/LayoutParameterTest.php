<?php
/**
 * Test pour l'aide de vue LayoutParameter
 *
 *
 * @project dafap/DafapLayout
 * @package tests/DafapLayoutTest/View/Helper
 * @filesource LayoutParameterTest.php
 * @encodage UTF-8
 * @author DAFAP Informatique - Alain Pomirol (dafap@free.fr)
 * @date 29 avr. 2014
 * @version 2014-1
 */
namespace DafapLayoutTest\View\Helper;

use PHPUnit_Framework_TestCase;
use DafapLayout\View\Helper\LayoutParameter;

/**
 * Test pour {@see DafapLayoutTest\View\Helper\LayoutParameter}
 * 
 * @author Alain Pomirol <pomirol@gmail.com>
 */
class LayoutParameterTest extends PHPUnit_Framework_TestCase
{
    public function testLayoutParameterReturnValue()
    {
        $plugin = new LayoutParameter();
        $this->assertSame('value', $plugin->__invoke('key', array('key' => 'value')));
    }
    
    public function testLayoutParameterReturnNull()
    {
        $plugin = new LayoutParameter();
        $this->assertSame(null, $plugin->__invoke('cle', array('key' => 'value')));
    }
    
    public function testLayoutParameterBadFirstParameter()
    {
        $plugin = new LayoutParameter();
        try {
            $plugin->__invoke(array(), array('key' => 'value'));
        } catch (\DafapLayout\View\Helper\Exception $attendu) {
            $this->assertEquals(1, $attendu->getCode(), 'Exception attendue mais pas le bon message.');
            return;
        }
        
        $this->fail('Une exception attendue n\'a pas été levée.');
    }

    public function testLayoutParameterBadSecondParameter()
    {
        $plugin = new LayoutParameter();
        try {
            $plugin->__invoke('key', 'value');
        } catch (\DafapLayout\View\Helper\Exception $attendu) {
            $this->assertEquals(2, $attendu->getCode(), 'Exception attendue mais pas le bon message.');
            return;
        }
    
        $this->fail('Une exception attendue n\'a pas été levée.');
    }
}