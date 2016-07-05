<?php

namespace Phossa2\Event;

/**
 * EventDispatcher test case.
 */
class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var EventDispatcher
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new EventDispatcher();

        require_once __DIR__ . '/MyClass.php';
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
     * Call protected/private method of a class.
     *
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    /**
     * getPrivateProperty
     *
     * @param 	string $propertyName
     * @return	the property
     */
    public function getPrivateProperty($propertyName)
    {
        $reflector = new \ReflectionClass($this->object);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->object);
    }

    /**
     * name globbing
     *
     * @covers Phossa2\Event\EventDispatcher::matchEventName
     */
    public function testMatchEventName()
    {
        $this->assertTrue($this->invokeMethod(
            'matchEventName',
            ['*', 'login']
        ));

        $this->assertTrue($this->invokeMethod(
            'matchEventName',
            ['login', 'login']
        ));

        $this->assertTrue($this->invokeMethod(
            'matchEventName',
            ['l*', 'login']
        ));

        $this->assertFalse($this->invokeMethod(
            'matchEventName',
            ['*.*', 'login']
        ));

        $this->assertFalse($this->invokeMethod(
            'matchEventName',
            ['l*.*', 'login']
        ));

        $this->assertTrue($this->invokeMethod(
            'matchEventName',
            ['*', 'login.test']
        ));

        $this->assertTrue($this->invokeMethod(
            'matchEventName',
            ['*.*', 'login.test']
        ));

        $this->assertFalse($this->invokeMethod(
            'matchEventName',
            ['*.*.*', 'login.test']
        ));

        $this->assertTrue($this->invokeMethod(
            'matchEventName',
            ['*.test', 'login.test']
        ));
    }

    /**
     * name globbing
     *
     * @covers Phossa2\Event\EventDispatcher::globEventNames
     */
    public function testGlobEventNames()
    {
        $data = ['*', '*.*', '*.*.*', 'l*', 'l*.*', 'login.*'];
        $this->assertEquals(
            ['*', '*.*', 'l*.*', 'login.*'],
            $this->invokeMethod(
                'globEventNames',
                ['login.test', $data]
            )
        );
    }

    /**
     * shared event manager
     *
     * @covers Phossa2\Event\EventDispatcher::addScope
     * @covers Phossa2\Event\EventDispatcher::getShareable
     * @covers Phossa2\Event\EventDispatcher::on
     * @covers Phossa2\Event\EventDispatcher::trigger
     */
    public function testSharedManager1()
    {
        $this->expectOutputString('scope_bingoBINGO');

        // event manager now belongs to $scope1 also
        $this->object->addScope('scope');

        // bind event to shared manager of the $scope1
        EventDispatcher::getShareable('scope')->on('*', function($evt) {
            echo "scope_" . $evt->getName();
        }, 10);

        // bind to self
        $this->object->on('bingo', function($evt) {
            echo "BINGO";
        });

        $this->object->trigger('bingo');
    }

    /**
     * shared event manager
     *
     * @covers Phossa2\Event\EventDispatcher::onEvent
     * @covers Phossa2\Event\EventDispatcher::offEvent
     */
    public function testSharedManager2()
    {
        $this->expectOutputString('wow');

        $this->object->addScope('x');
        EventDispatcher::onEvent('x', '*', function($evt) {
            echo $evt->getName();
        });

        $this->object->trigger('wow');

        EventDispatcher::offEvent('x');
        $this->object->trigger('wow');
    }

    /**
     * listener aware
     *
     * @covers Phossa2\Event\EventDispatcher::attachListener
     * @covers Phossa2\Event\EventDispatcher::detachListener
     */
    public function testAttachListener()
    {
        $this->expectOutputString('xxx');

        $listener = new \MyClass();

        // attach
        $this->object->attachListener($listener);
        $this->object->trigger('afterTest');

        // detach
        $this->object->detachListener($listener, 'afterTest');
        $this->object->trigger('afterTest');
    }

    /**
     * countable
     *
     * @covers Phossa2\Event\EventDispatcher::one
     * @covers Phossa2\Event\EventDispatcher::many
     * @covers Phossa2\Event\EventDispatcher::off
     */
    public function testOne()
    {
        $this->expectOutputString('onetwotwothree');

        // one
        $this->object->one('one', function() {
            echo "one";
        });
        $this->object->trigger('one');
        $this->object->trigger('one');

        // many
        $this->object->many(2, 'two', function() {
            echo "two";
        });
        $this->object->trigger('two');
        $this->object->trigger('two');

        // off
        $this->object->many(3, 'three', function() {
            echo "three";
        });
        $this->object->trigger('three');
        $this->object->off('three');
        $this->object->trigger('three');
    }
}
