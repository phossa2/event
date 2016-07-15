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

use Phossa2\Event\Traits\CountableTrait;
use Phossa2\Event\Traits\NameGlobbingTrait;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Traits\SharedManagerTrait;
use Phossa2\Event\Traits\ListenerAwareTrait;
use Phossa2\Event\Traits\EventPrototypeTrait;
use Phossa2\Event\Interfaces\CountableInterface;
use Phossa2\Event\Interfaces\SharedManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;
use Phossa2\Event\Interfaces\EventPrototypeInterface;

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
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated
 * @since   2.1.1 added EventPrototype
 */
class EventDispatcher extends EventManager implements SharedManagerInterface, ListenerAwareInterface, CountableInterface, EventPrototypeInterface
{
    use CountableTrait,
        NameGlobbingTrait,
        SharedManagerTrait,
        ListenerAwareTrait,
        EventPrototypeTrait;

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
        $this->setEventPrototype($event_proto);
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
}
