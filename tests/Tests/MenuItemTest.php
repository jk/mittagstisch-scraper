<?php
namespace JK\Mittagstisch\Tests;

use JK\Mittagstisch\MenuItem;

class MenuItemTest extends \PHPUnit_Framework_TestCase
{
    /** @var MenuItem */
    private $instance;

    public function setUp()
    {
        $this->instance = new MenuItem('Menüname', 7.77);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('JK\Mittagstisch\MenuItem', $this->instance);
    }

    public function testGetLabel()
    {
        $this->assertEquals('Menüname', $this->instance->getLabel());
    }

    public function testGetPrice()
    {
        $this->assertEquals('7.77', $this->instance->getPrice());
    }

    public function testToString()
    {
        $this->assertEquals('Menüname 7.77', $this->instance);
    }
}
