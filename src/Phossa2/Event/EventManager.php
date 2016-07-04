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

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventQueueInterface;
use Phossa2\Event\Interfaces\EventManagerInterface;

/**
 * EventManager
 *
 * A basic implementation of EventManagerInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventManagerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EventManager extends ObjectAbstract implements EventManagerInterface
{
    /**
     * Events managing
     *
     * @var    EventQueueInterface[]
     * @access protected
     */
    protected $events = [];

    /**
     * {@inheritDoc}
     */
    public function on(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        if (!$this->hasEventQueue($eventName)) {
            $this->events[$eventName] = $this->newEventQueue();
        }
        $this->events[$eventName]->insert($callable, $priority);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function off(
        /*# string */ $eventName,
        callable $callable = null
    ) {
        if ($this->hasEventQueue($eventName)) {
            if (null === $callable) {
                // remove all
                $this->events[$eventName]->flush();
            } else {
                // remove callable
                $this->events[$eventName]->remove($callable);
            }

            // when count is zeror, remove queue
            if (count($this->events[$eventName]) === 0) {
                unset($this->events[$eventName]);
            }
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function trigger($event, $context = null, array $properties = [])
    {
        // make sure is an event
        $evt = $this->newEvent($event, $context, $properties);

        // get handler queue
        $queue = $this->getMatchedQueue($evt->getName());

        // walk thru the queue
        foreach ($queue as $q) {
            // execute the handler
            $q['data']($evt);

            // break out if event stopped
            if ($evt->isPropagationStopped()) {
                break;
            }
        }

        return $this;
    }

    /**
     * Has $eventName been defined ?
     *
     * @param  string $eventName
     * @return bool
     * @access protected
     */
    protected function hasEventQueue(/*# string */ $eventName)/*# : bool */
    {
        return isset($this->events[$eventName]);
    }

    /**
     * Create a new event
     *
     * @param  string|EventInterface $eventName
     * @param  object|string|null $context
     * @param  array $properties
     * @return EventInterface
     * @access protected
     */
    protected function newEvent(
        $eventName,
        $context,
        array $properties
    )/*# : EventInterface */ {
        if (is_object($eventName)) {
            return $eventName;
        } else {
            return new Event($eventName, $context, $properties);
        }
    }

    /**
     * Get a new event queue
     *
     * @return EventQueueInterface
     * @access protected
     */
    protected function newEventQueue()/*# : EventQueueInterface */
    {
        return new EventQueue();
    }

    /**
     * Get related event handler queue for this $eventName
     *
     * @param  string $eventName
     * @return EventQueueInterface
     * @access protected
     */
    protected function getMatchedQueue(
        /*# : string */ $eventName
    )/*# : EventQueueInterface */ {
        if ($this->hasEventQueue($eventName)) {
            return $this->events[$eventName];
        } else {
            return $this->newEventQueue();
        }
    }
}
