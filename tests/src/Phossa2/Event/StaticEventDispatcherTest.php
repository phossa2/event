<?php

namespace Phossa2\Event;

/**
 * StaticEventDispatcher test case.
 */
class StaticEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Test default event manager
     *
     * @covers Phossa2\Event\StaticEventDispatcher::getEventManager
     */
    public function testGetEventManager()
    {
        $events = StaticEventDispatcher::getEventManager();
        $this->assertEquals('__STATIC__', $events->isShareable());
    }

    /**
     * @covers Phossa2\Event\StaticEventDispatcher::setEventManager
     * @covers Phossa2\Event\StaticEventDispatcher::getEventManager
     */
    public function testSetEventManager()
    {
        $events = new EventDispatcher();
        StaticEventDispatcher::setEventManager($events);
        $this->assertTrue($events === StaticEventDispatcher::getEventManager());
    }

    /**
     * @covers Phossa2\Event\StaticEventDispatcher::attach
     * @covers Phossa2\Event\StaticEventDispatcher::clearListeners
     * @covers Phossa2\Event\StaticEventDispatcher::trigger
     */
    public function testAttach()
    {
        $this->expectOutputString('*t*t*');

        StaticEventDispatcher::attach('*', function() {
            echo '*';
        }, 100);

        StaticEventDispatcher::attach('t*', function() {
            echo 't*';
        }, 20);

        StaticEventDispatcher::trigger('test');

        StaticEventDispatcher::clearListeners('*');
        StaticEventDispatcher::trigger('test');
    }
}
