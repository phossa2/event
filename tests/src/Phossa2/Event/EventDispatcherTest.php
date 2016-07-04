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
     * test shared event manager
     *
     * @covers Phossa2\Event\EventDispatcher::addScope
     * @covers Phossa2\Event\EventDispatcher::getShareable
     * @covers Phossa2\Event\EventDispatcher::on
     * @covers Phossa2\Event\EventDispatcher::trigger
     */
    public function testSharedManager()
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
}
