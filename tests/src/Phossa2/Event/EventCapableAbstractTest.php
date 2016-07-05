<?php

namespace Phossa2\Event;

/**
 * EventCapableAbstract test case.
 */
class EventCapableAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        require_once __DIR__ . '/MyClass.php';
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @covers Phossa2\Event\EventCapableAbstract::setEventManager
     * @covers Phossa2\Event\EventCapableAbstract::getEventManager
     */
    public function testSetEventManager()
    {
        $em  = new EventDispatcher('TEST');
        $obj = new \MyClass();

        // assign the new manager
        $obj->setEventManager($em);
        $this->assertTrue($em === $obj->getEventManager());
    }

    /**
     * Test class's default event manager
     *
     * @covers Phossa2\Event\EventCapableAbstract::getEventManager
     */
    public function testGetEventManager()
    {
        // default manager
        $obj = new \MyClass();
        $em  = $obj->getEventManager();
        $this->assertTrue($em->hasScope('MyClass'));
    }

    /**
     * @covers Phossa2\Event\EventCapableAbstract::setEventPrototype
     */
    public function testSetEventPrototype()
    {
        // default manager
        $obj = new \MyClass();
        $obj->setEventPrototype(new \MyEvent('test'));

        $evt = $obj->triggerEvent('wow');
        $this->assertTrue($evt instanceof \MyEvent);
        $this->assertEquals('wow', $evt->getName());
    }

    /**
     * @covers Phossa2\Event\EventCapableAbstract::triggerEvent
     */
    public function testTriggerEvent()
    {
        $this->expectOutputString('xxxyyy');

        // an interface level event manager
        EventDispatcher::getShareable('MyInterface')
            ->on('*', function() { echo "yyy";}, 60);

        // event capable class implmenting 'MyInterface'
        $obj = new \MyClass();

        // will auto attach events defined `eventsListening()`
        // will trigger its own handler and interface level handler
        $obj->triggerEvent('afterTest');
    }
}
