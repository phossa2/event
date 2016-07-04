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
     * @covers Phossa2\Event\Event::__invoke()
     */
    public function testInvoke()
    {
        $evt = new Event('prototype');
        $evt('newEvent', $this->object, ['bingo', 'a' => 'c']);
        $this->assertTrue('newEvent' === $evt->getName());
        $this->assertTrue($this->object === $evt->getContext());
        $this->assertEquals(['bingo', 'a' => 'c'], $evt->getProperties());
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
     * @covers Phossa2\Event\Event::setContext
     */
    public function testSetContext1()
    {
        // context is an object
        $this->assertTrue($this === $this->object->getContext());

        // set context to class name
        $this->object->setContext(get_class($this->object));

        // test class
        $this->assertEquals(
            get_class($this->object),
            $this->object->getContext()
        );
    }

    /**
     * set context to a non-exist class name
     *
     * @covers Phossa2\Event\Event::setContext
     * @expectedExceptionCode Phossa2\Event\Message\Message::EVT_CONTEXT_INVALID
     * @expectedException Phossa2\Event\Exception\InvalidArgumentException
     */
    public function testSetContext2()
    {
        $this->object->setContext('Event');
    }

    /**
     * @covers Phossa2\Event\Event::getContext
     */
    public function testGetContext()
    {
        $this->object->setContext(get_class($this->object));
        $this->assertTrue(
            get_class($this->object) === $this->object->getContext()
        );
    }

    /**
     * @covers Phossa2\Event\Event::hasProperty
     */
    public function testHasProperty()
    {
        $this->assertTrue($this->object->hasProperty('invoker'));
        $this->assertFalse($this->object->hasProperty(10));
    }

    /**
     * @covers Phossa2\Event\Event::getProperty
     */
    public function testGetProperty1()
    {
        $this->assertTrue($this === $this->object->getProperty('invoker'));
        $this->assertTrue(null === $this->object->getProperty('wow'));
    }

    /**
     * @covers Phossa2\Event\Event::setProperty
     */
    public function testSetProperty()
    {
        $this->object->setProperty('wow', 'bingo');
        $this->assertTrue('bingo' === $this->object->getProperty('wow'));

        // name is int , ok
        $this->object->setProperty(10, 'bingo');
    }

    /**
     * @covers Phossa2\Event\Event::getProperties
     */
    public function testGetProperties()
    {
        $p = $this->object->getProperties();
        $this->assertArrayHasKey('invoker', $p);
    }

    /**
     * @covers Phossa2\Event\Event::setProperties
     */
    public function testSetProperties()
    {
        $a = ['a' => 'aa', 'b' => 'bb'];
        $this->object->setProperties($a);
        $this->assertTrue($a === $this->object->getProperties());
    }

    /**
     * @covers Phossa2\Event\Event::addResult
     * @covers Phossa2\Event\Event::getResults
     */
    public function testAddResult()
    {
        $this->object->addResult('bingo');
        $this->assertEquals(['bingo'], $this->object->getResults());

        $this->object->addResult('wow');
        $this->assertEquals(
            ['bingo', 'wow'],
            $this->object->getResults()
        );
    }

    /**
     * @covers Phossa2\Event\Event::stopPropagation
     * @covers Phossa2\Event\Event::isPropagationStopped
     */
    public function testStopPropagation()
    {
        $this->assertTrue(false === $this->object->isPropagationStopped());
        $this->object->stopPropagation();
        $this->assertTrue(true === $this->object->isPropagationStopped());
    }

    /**
     * @covers Phossa2\Event\Event::offsetExists
     * @covers Phossa2\Event\Event::offsetGet
     * @covers Phossa2\Event\Event::offsetSet
     * @covers Phossa2\Event\Event::offsetUnset
     */
    public function testOffsetExists()
    {
        // exists
        $this->assertTrue(isset($this->object['invoker']));

        // get
        $this->assertTrue($this === $this->object['invoker']);

        // set
        $this->object['wow'] = 'bingo';
        $this->assertTrue('bingo' === $this->object['wow']);

        // unset
        $this->object['wow'] = null;
        $this->assertFalse(isset($this->object['wow']));
    }
}

