<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Event
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa2\Event;

use Phossa2\Event\Message\Message;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Exception\InvalidArgumentException;

/**
 * Event
 *
 * Basic implementation of EventInterface with array access to properties
 *
 * ```php
 * // create event
 * $evt = new Event(
 *     'login.attempt',         // event name
 *     $this,                   // event context
 *     ['username' => 'phossa'] // event properties
 * );
 *
 * // get/set event property
 * if ('phossa' === $evt['username']) {
 *     $evt['username'] = 'phossa2';
 * }
 *
 * // stop event
 * $evt->stopPropagation();
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Event extends ObjectAbstract implements EventInterface
{
    /**
     * event name
     *
     * @var    string
     * @access protected
     */
    protected $name;

    /**
     * event context
     *
     * an object OR static class name (string)
     *
     * @var    object|string
     * @access protected
     */
    protected $context;

    /**
     * event properties
     *
     * @var    array
     * @access protected
     */
    protected $properties;

    /**
     * Results from the handlers
     *
     * @var    array
     * @access protected
     */
    protected $results = [];

    /**
     * stop propagation
     *
     * @var    bool
     * @access protected
     */
    protected $stopped = false;

    /**
     * Constructor
     *
     * @param  string $eventName event name
     * @param  string|object $context event context, object or static classname
     * @param  array $properties (optional) event properties
     * @throws InvalidArgumentException if arguments not right
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $eventName,
        $context = null,
        array $properties = []
    ) {
        $this->__invoke($eventName, $context, $properties);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(
        /*# string */ $eventName,
        $context = null,
        array $properties = []
    ) {
        return $this->setName($eventName)
            ->setContext($context)
            ->setProperties($properties);
    }

    /**
     * {@inheritDoc}
     */
    public function setName(/*# string */ $eventName)
    {
        if (!is_string($eventName) || empty($eventName)) {
            throw new InvalidArgumentException(
                Message::get(Message::EVT_NAME_INVALID, $eventName),
                Message::EVT_NAME_INVALID
            );
        }
        $this->name = $eventName;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()/*# : string */
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext($context)
    {
        if (is_null($context) ||
            is_object($context) ||
            is_string($context) && class_exists($context, false)) {
            $this->context = $context;
            return $this;
        }
        throw new InvalidArgumentException(
            Message::get(Message::EVT_CONTEXT_INVALID, $context),
            Message::EVT_CONTEXT_INVALID
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty(/*# string */ $name)/*#: bool */
    {
        return isset($this->properties[(string) $name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(/*# string */ $name)
    {
        if ($this->hasProperty($name)) {
            return $this->properties[(string) $name];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setProperty(/*# string */ $name, $value)
    {
        if (null === $value) {
            unset($this->properties[(string) $name]);
        } else {
            $this->properties[(string) $name] = $value;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties()/*# : array */
    {
        return $this->properties;
    }

    /**
     * {@inheritDoc}
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addResult($result)
    {
        $this->results[] = $result;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getResults()/*# : array */
    {
        return $this->results;
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation(/*# bool */ $stop = true)
    {
        $this->stopped = (bool) $stop;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped()/*# : bool */
    {
        return $this->stopped;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)/*# : bool */
    {
        return $this->hasProperty($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->getProperty($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->setProperty($offset, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        $this->setProperty($offset, null);
    }
}
