<?php
namespace JK\Mittagstisch\Tests;

use JK\Mittagstisch\Scrapers\Bullerei;

class BullereiTest extends \PHPUnit_Framework_TestCase
{
    /** @var Bullerei */
    private $instance;

    public function setUp()
    {
        $this->instance = new Bullerei();
    }

    public function testInstance()
    {
        static::assertInstanceOf('JK\Mittagstisch\Scrapers\Bullerei', $this->instance);
    }

    public function testScrape()
    {
        $menus = $this->invokeScrape($this->instance, 'scrape');
        static::assertInternalType('array', $menus);
        static::assertContainsOnlyInstancesOf('JK\Mittagstisch\MenuItem', $menus);
    }

    public function invokeScrape(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
