<?php
namespace Source\Domain\Test;

use PHPUnit\Framework\TestCase;
use Source\Domain\Model\MyDomain;

/**
 * MyDomainTest Domain\Test
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Domain\Test
 */
class MyDomainTest extends TestCase
{
    public function testCalculate()
    {
        $myDomain = new MyDomain();
        $this->assertEquals(4, $myDomain->calculate(2));
    }

    public function testIsString()
    {
        $myDomain = new MyDomain();
        $this->assertIsString($myDomain->isString("teste"));
    }
}
