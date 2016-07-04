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
     */
    public function testMatchEventName()
    {
        $this->expectOutputString('xxxyyy');

        EventDispatcher::getShareable('MyInterface')->on('*', function() {
            echo "yyy";
        }, 60);

        $obj = new \MyClass();

        // will print 'xxxyyy'
        $obj->triggerEvent('afterTest');
    }
}
