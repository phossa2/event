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
use Phossa2\Event\Interfaces\EventResultInterface;
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
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated to use the new EventManagerInterface
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
    public function attach($event, $callback, $priority = 0)
    {
        if (!$this->hasEvent($event)) {
            $this->events[$event] = $this->newEventQueue();
        }
        $this->events[$event]->insert($callback, $priority);
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function detach($event, $callback)
    {
        if ($this->hasEvent($event)) {
            $this->removeEventCallable($event, $callback);
        } elseif ('' === $event) {
            $this->events = []; // remove all events
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function clearListeners($event)
    {
        $this->detach($event, null);
    }

    /**
     * {@inheritDoc}
     */
    public function trigger($event, $target = null, $argv = array())
    {
        // result
        $res = true;

        // make sure is an event
        $evt = $this->newEvent($event, $target, $argv);

        // get handler queue
        $queue = $this->getMatchedQueue($evt->getName());
        foreach ($queue as $q) {
            // execute the handler
            $res = $q['data']($evt);

            if ($evt instanceof EventResultInterface) {
                $evt->addResult($res);
            }
            if ($evt->isPropagationStopped()) {
                break;
            }
        }

        return $res;
    }

    /**
     * Has $eventName been bound ?
     *
     * @param  string $eventName
     * @return bool
     * @access protected
     */
    protected function hasEvent(/*# string */ $eventName)/*# : bool */
    {
        return isset($this->events[$eventName]);
    }

    /**
     * Create a new event
     *
     * @param  string|EventInterface $eventName
     * @param  object|string|null $target
     * @param  array $parameters
     * @return EventInterface
     * @access protected
     */
    protected function newEvent(
        $eventName,
        $target,
        array $parameters
    )/*# : EventInterface */ {
        if (is_object($eventName)) {
            return $eventName;
        } else {
            return new Event($eventName, $target, $parameters);
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
        if ($this->hasEvent($eventName)) {
            return $this->events[$eventName];
        } else {
            return $this->newEventQueue();
        }
    }

    /**
     * Remove event or its callable
     *
     * @param  string $eventName
     * @param  callable|null $callable
     * @access protected
     */
    protected function removeEventCallable(
        /*# string */ $eventName,
        $callable
    ) {
        if (null === $callable) {
            // remove all callables
            $this->events[$eventName]->flush();
        } else {
            // remove one callable
            $this->events[$eventName]->remove($callable);
        }

        // when count is zeror, remove queue
        if (count($this->events[$eventName]) === 0) {
            unset($this->events[$eventName]);
        }
    }
}
