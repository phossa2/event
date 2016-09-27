<?php

namespace Phossa2\Event;

/**
 * Event test case.
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Event
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new Event('test', $this, ['invoker' => $this]);
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
     * @covers Phossa2\Event\Event::setName
     */
    public function testSetName()
    {
        $newname = 'newname';
        $this->object->setName($newname);
        $this->assertTrue($newname === $this->object->getName());
    }

    /**
     * set empty name
     *
     * @covers Phossa2\Event\Event::setName
     * @expectedExceptionCode Phossa2\Event\Message\Message::EVT_NAME_INVALID
     * @expectedException Phossa2\Event\Exception\InvalidArgumentException
     */
    public function testSetName2()
    {
        $newname = '';
        $this->object->setName($newname);
        $this->assertTrue($newname === $this->object->getName());
    }

    /**
     * set non-string name
     *
     * @covers Phossa2\Event\Event::setName
     * @expectedExceptionCode Phossa2\Event\Message\Message::EVT_NAME_INVALID
     * @expectedException Phossa2\Event\Exception\InvalidArgumentException
     */
    public function testSetName3()
    {
        $newname = 100;
        $this->object->setName($newname);
        $this->assertTrue($newname === $this->object->getName());
    }

    /**
     * @covers Phossa2\Event\Event::getName
     */
    public function testGetName()
    {
        $this->assertTrue('test' === $this->object->getName());
    }

    /**
     * @covers Phossa2\Event\Event::setTarget
     */
    public function testSetTarget1()
    {
        // Target is an object
        $this->assertTrue($this === $this->object->getTarget());

        // set Target to class name
        $this->object->setTarget(get_class($this->object));

        // test class
        $this->assertEquals(
            get_class($this->object),
            $this->object->getTarget()
        );
    }

    /**
     * @covers Phossa2\Event\Event::getTarget
     */
    public function testGetTarget()
    {
        $this->object->setTarget(get_class($this->object));
        $this->assertTrue(
            get_class($this->object) === $this->object->getTarget()
        );
    }

    /**
     * @covers Phossa2\Event\Event::getParam
     */
    public function testGetParam1()
    {
        $this->assertTrue($this === $this->object->getParam('invoker'));
        $this->assertTrue(null === $this->object->getParam('wow'));
    }

    /**
     * @covers Phossa2\Event\Event::getParams
     */
    public function testGetParams()
    {
        $p = $this->object->getParams();
        $this->assertArrayHasKey('invoker', $p);
    }

    /**
     * @covers Phossa2\Event\Event::setParams
     */
    public function testSetParams()
    {
        $a = ['a' => 'aa', 'b' => 'bb'];
        $this->object->setParams($a);
        $this->assertTrue($a === $this->object->getParams());
    }

    /**
     * @covers Phossa2\Event\Event::stopPropagation
     * @covers Phossa2\Event\Event::isPropagationStopped
     */
    public function testStopPropagation()
    {
        $this->assertTrue(false === $this->object->isPropagationStopped());
        $this->object->stopPropagation(true);
        $this->assertTrue(true === $this->object->isPropagationStopped());
    }

}

