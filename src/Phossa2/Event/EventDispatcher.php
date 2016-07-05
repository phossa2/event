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

use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Traits\NameGlobbingTrait;
use Phossa2\Event\Traits\SharedManagerTrait;
use Phossa2\Event\Traits\ListenerAwareTrait;
use Phossa2\Event\Interfaces\CountableInterface;
use Phossa2\Event\Interfaces\SharedManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;

/**
 * EventDispatcher
 *
 * Advanced event manager with
 *
 * - event name globbing
 * - shared manager support
 * - attach/detach listener
 * - able to trigger an event with countable times
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EventDispatcher extends EventManager implements SharedManagerInterface, ListenerAwareInterface, CountableInterface
{
    use NameGlobbingTrait,
        SharedManagerTrait,
        ListenerAwareTrait;

    /**
     * event prototype
     *
     * @var    EventInterface|null
     * @access protected
     */
    protected $event_proto;

    /**
     * callable mapping
     *
     * @var    callable[];
     * @access protected
     */
    protected $callable_map = [];

    /**
     * Create a event manager with defined scopes
     *
     * @param  string|array $scopes
     * @param  EventInterface $event_proto event prototype if any
     * @access public
     */
    public function __construct(
        $scopes = '',
        EventInterface $event_proto = null
    ) {
        // set scopes
        if ('' !== $scopes) {
            $this->scopes = (array) $scopes;
        }

        // set event prototype
        $this->event_proto = $event_proto;
    }

    /**
     * {@inheritDoc}
     */
    public function many(
        /*# int */ $times,
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        // wrap the callable
        $wrapper = function (EventInterface $event) use ($callable, $times) {
            static $cnt = 0;
            if ($cnt++ < $times) {
                call_user_func($callable, $event);
            }
        };

        // mapping callable
        $oid = $this->hashCallable($callable);
        $this->callable_map[$eventName][$oid] = $wrapper;

        // bind wrapper instead of the $callable
        $this->on($eventName, $wrapper, $priority);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function one(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        return $this->many(1, $eventName, $callable, $priority);
    }

    /**
     * Override `off()` in EventManager
     *
     * Added support for countable callable
     *
     * {@inheritDoc}
     */
    public function off(
        /*# string */ $eventName = '',
        callable $callable = null
    ) {
        if (null !== $callable) {
            $oid = $this->hashCallable($callable);
            if (isset($this->callable_map[$eventName][$oid])) {
                $callable = $this->callable_map[$eventName][$oid];
                unset($this->callable_map[$eventName][$oid]);
            }
        } else {
            unset($this->callable_map[$eventName]);
        }
        return parent::off($eventName, $callable);
    }

    /**
     * Override `newEvent()` in EventManager.
     *
     * Added event prototype support
     *
     * {@inheritDoc}
     */
    protected function newEvent(
        $eventName,
        $context,
        array $properties
    )/*# : EventInterface */ {
        if (is_object($eventName)) {
            return $eventName;
        } elseif ($this->event_proto) {
            $evt = clone $this->event_proto;
            return $evt($eventName, $context, $properties);
        } else {
            return new Event($eventName, $context, $properties);
        }
    }

    /**
     * Override `getMatchedQueue()` in EventManager.
     *
     * Support for shared manager & name globbing
     *
     * {@inheritDoc}
     */
    protected function getMatchedQueue(
        /*# : string */ $eventName
    )/*# : EventQueueInterface */ {
        // get all shared managers
        $managers = $this->getShareables();

        // add $this manager
        array_unshift($managers, $this);

        /* @var $mgr EventDispatcher */
        $nqueue = $this->newEventQueue();
        foreach ($managers as $mgr) {
            $nqueue = $nqueue->combine($mgr->matchEventQueues($eventName));
        }
        return $nqueue;
    }

    /**
     * Get all event names of $this manager
     *
     * @return array
     * @access protected
     */
    protected function getEventNames()/*# : array */
    {
        return array_keys($this->events);
    }

    /**
     * Get a merged queue in $this manager for the given event name
     *
     * @param  string $eventName
     * @return EventQueueInterface
     * @access protected
     */
    protected function matchEventQueues(
        /*# string */ $eventName
    )/*: EventQueueInterface */ {
        $nqueue = $this->newEventQueue();
        $names  = $this->globEventNames($eventName, $this->getEventNames());
        foreach ($names as $evtName) {
            if ($this->hasEvent($evtName)) {
                $nqueue = $nqueue->combine($this->events[$evtName]);
            }
        }
        return $nqueue;
    }

    /**
     * Returns a unique id for $callable with $eventName
     *
     * @param  callable $callable
     * @return string
     * @access protected
     */
    protected function hashCallable(callable $callable)/*# string */
    {
        return spl_object_hash((object) $callable);
    }
}
