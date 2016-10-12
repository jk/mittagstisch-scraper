<?php
namespace JK\Mittagstisch\Scrapers\Tests;

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
        $this->assertInstanceOf('JK\Mittagstisch\Scrapers\BaseRestaurant', $this->instance);
        /* @todo check for restaurant interface implementation */
    }

    public function testGetHomepage()
    {
        $homepage = $this->instance->getHomepage();
        $this->assertStringStartsWith('http', $homepage);
        $this->assertTrue((mb_strlen($homepage) > 10));
    }

    public function testGetName()
    {
        $name = $this->instance->getName();
        $this->assertTrue((mb_strlen($name) > 2));
    }

    public function testGetLongitude()
    {
        $longitude = $this->instance->getLongitude();
        $this->assertTrue(is_float($longitude));
        $this->assertTrue($longitude > -180 && $longitude < 180);
    }

    public function testGetLatitude()
    {
        $latitude = $this->instance->getLatitude();
        $this->assertTrue(is_float($latitude));
        $this->assertTrue($latitude > -90 && $latitude < 90);
    }
}
