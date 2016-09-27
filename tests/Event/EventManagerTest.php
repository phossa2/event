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
     * @covers Phossa2\Event\EventManager::attach
     * @covers Phossa2\Event\EventManager::trigger
     */
    public function testAttach1()
    {
        $this->expectOutputString('test1test1');

        $this->object->attach('test1', function(EventInterface $evt) {
            echo $evt->getName();
        });
        $this->object->trigger('test1');
        $this->object->trigger('test1');
    }

    /**
     * Test callable priority
     *
     * @covers Phossa2\Event\EventManager::attach
     * @covers Phossa2\Event\EventManager::trigger
     */
    public function testAttach2()
    {
        $this->expectOutputString('callable1callable2callable2callable1');

        $callable1 = function(EventInterface $evt) {
            echo "callable1";
        };
        $callable2 = function(EventInterface $evt) {
            echo "callable2";
        };

        $this->object->attach('test1', $callable1, 80);
        $this->object->attach('test1', $callable2, 20);

        $this->object->trigger('test1');

        // adjust callable priority
        $this->object->attach('test1', $callable2, 90);
        $this->object->trigger('test1');
    }

    /**
     * testing detach one callable
     *
     * @covers Phossa2\Event\EventManager::detach
     */
    public function testDetach1()
    {
        $this->expectOutputString('callable1callable2callable2');

        $callable1 = function(EventInterface $evt) {
            echo "callable1";
        };
        $callable2 = function(EventInterface $evt) {
            echo "callable2";
        };

        // bind
        $this->object->attach('test1', $callable1);
        $this->object->attach('test1', $callable2);

        // trigger
        $this->object->trigger('test1');

        // off one callable
        $this->object->detach('test1', $callable1);

        // trigger remaining callable
        $this->object->trigger('test1');
    }

    /**
     * testing detach one eventName
     *
     * @covers Phossa2\Event\EventManager::detach
     */
    public function testDetach2()
    {
        $this->expectOutputString('callable1callable2');

        $callable1 = function(EventInterface $evt) {
            echo "callable1";
        };
        $callable2 = function(EventInterface $evt) {
            echo "callable2";
        };

        $this->object->attach('test1', $callable1);
        $this->object->attach('test1', $callable2);
        $this->object->trigger('test1');

        // off one event
        $this->object->detach('test1', null);
        $this->object->trigger('test1');
    }

    /**
     * testing detach all events
     *
     * @covers Phossa2\Event\EventManager::clearListeners
     */
    public function testDetach3()
    {
        $this->expectOutputString('callable1callable2');

        $callable1 = function(EventInterface $evt) {
            echo "callable1";
        };
        $callable2 = function(EventInterface $evt) {
            echo "callable2";
        };

        $this->object->attach('test1', $callable1);
        $this->object->attach('test2', $callable2);
        $this->object->trigger('test1');
        $this->object->trigger('test2');

        // off all events
        $this->object->clearListeners('');

        $this->object->trigger('test1');
        $this->object->trigger('test2');
    }
}

