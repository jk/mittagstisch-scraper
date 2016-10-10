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
        static::assertInstanceOf('JK\Mittagstisch\MenuItem', $this->instance);
    }

    public function testGetLabel()
    {
        static::assertEquals('Menüname', $this->instance->getLabel());
    }

    public function testGetPrice()
    {
        static::assertEquals('7.77', $this->instance->getPrice());
    }

    public function testToString()
    {
        static::assertEquals('Menüname 7.77', $this->instance);
    }
}
