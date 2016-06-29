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

namespace Phossa2\Event\Interfaces;

use Phossa2\Event\EventResult;
use Phossa2\Event\Message\Message;
use Phossa2\Event\Exception\InvalidArgumentException;

/**
 * Implementation of EventInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait EventTrait
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
     * @var    EventResult
     * @access protected
     */
    protected $results;

    /**
     * stop propagation
     *
     * @var    bool
     * @access protected
     */
    protected $stopped = false;

    /**
     * Init this event
     *
     * @param  string $eventName
     * @param  object|string $context
     * @param  array $properties
     * @return $this
     * @throws InvalidArgumentException if arguments not right
     * @access public
     * @api
     */
    public function __invoke(
        /*# string */ $eventName,
        $context,
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
        if (is_object($context) ||
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
        if (is_null($this->results)) {
            $this->results = new EventResult();
        }
        $this->results->push($result);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getResults()/*# : EventResult */
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
}
