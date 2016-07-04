<?php

namespace Phossa2\Event;

use Phossa2\Event\Interfaces\EventInterface;

/**
 * EventManager test case.
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var EventManager
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new EventManager();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * @covers Phossa2\Event\EventManager::on
     * @covers Phossa2\Event\EventManager::trigger
     */
    public function testOn()
    {
        $this->expectOutputString('test1test1');

        $this->object->on('test1', function(EventInterface $evt) {
            echo $evt->getName();
        });
        $this->object->trigger('test1');
        $this->object->trigger('test1');
    }

    /**
     * off one callable
     *
     * @covers Phossa2\Event\EventManager::off
     */
    public function testOff1()
    {
        $this->expectOutputString('callable2');

        $callable1 = function(EventInterface $evt) {
            echo "callable1";
        };
        $callable2 = function(EventInterface $evt) {
            echo "callable2";
        };

        $this->object->on('test1', $callable1);
        $this->object->on('test1', $callable2);

        $this->object->off('test1', $callable1);

        $this->object->trigger('test1');
    }

    /**
     * off one eventName
     *
     * @covers Phossa2\Event\EventManager::off
     */
    public function testOff2()
    {
        $callable1 = function(EventInterface $evt) {
            echo "callable1";
        };
        $callable2 = function(EventInterface $evt) {
            echo "callable2";
        };

        $this->object->on('test1', $callable1);
        $this->object->on('test1', $callable2);

        $this->object->off('test1');

        $this->object->trigger('test1');
    }
}

