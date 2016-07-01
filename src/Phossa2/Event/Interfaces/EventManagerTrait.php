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

use Phossa2\Event\EventQueue;

/**
 * EventManagerTrait
 *
 * Implementation of EventManagerInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventManagerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait EventManagerTrait
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
     * Unbind a callable from a specific eventName
     *
     * @param  string $eventName
     * @param  callable $callable
     * @return $this
     * @access public
     * @api
     */
    public function off(
        /*# string */ $eventName,
        callable $callable
    ) {
        if ($this->hasEventQueue($eventName)) {
            // remove callable
            $this->events[$eventName]->remove($callable);

            // clear event if no more handlers
            if (count($this->events[$eventName]) === 0) {
                $this->clearEventQueue($eventName);
            }
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function triggerEvent(EventInterface $event)
    {
        return $this->executeQueue(
            $event,
            $this->getMatchedQueue($event->getName())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function hasEventQueue(/*# string */ $eventName)/*# : bool */
    {
        return isset($this->events[$eventName]);
    }

    /**
     * {@inheritDoc}
     */
    public function getEventQueue(/*# string */ $eventName) {
        if (isset($this->events[$eventName])) {
            return $this->events[$eventName];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventNames()/*# : array */
    {
        return array_keys($this->events);
    }

    /**
     * {@inheritDoc}
     */
    public function clearEventQueue(/*# string */ $eventName)
    {
        unset($this->events[$eventName]);
        return $this;
    }

    /**
     * Execute the event queue on event
     *
     * @param  EventInterface $event
     * @param  EventQueueInterface $queue
     * @return $this
     * @access protected
     */
    protected function executeQueue(
        EventInterface $event,
        EventQueueInterface $queue
    ){
        foreach ($queue as $q) {
            $callable = $q['data'];

            $result = $callable($event);

            // add result into event
            $event->addResult($result);

            // stop propagation if callable returns FALSE
            if ($result === false) {
                $event->stopPropagation();
            }

            // break if event stopped
            if ($event->isPropagationStopped()) {
                break;
            }
        }
        return $this;
    }

    /**
     * Get a merged queue in THIS manager for event names provided
     *
     * @param  array $names
     * @return EventQueueInterface
     * @access protected
     */
    protected function matchEventQueues(
        /*# array */ $names
    )/*: EventQueueInterface */ {
        $nqueue = $this->newEventQueue();
        foreach ($names as $evtName) {
            if ($this->hasEventQueue($evtName)) {
                $nqueue = $nqueue->combine($this->getEventQueue($evtName));
            }
        }
        return $nqueue;
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

    abstract protected function getMatchedQueue(/*# : string */ $eventName)/*# : EventQueueInterface */;
}
