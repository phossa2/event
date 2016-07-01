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

use Phossa2\Event\Interfaces\NameGlobbingTrait;
use Phossa2\Event\Interfaces\SharedManagerTrait;
use Phossa2\Event\Interfaces\ListenerAwareTrait;
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
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EventDispatcher extends EventManager implements SharedManagerInterface, ListenerAwareInterface
{
    use SharedManagerTrait, NameGlobbingTrait, ListenerAwareTrait;

    /**
     * Create a event manager with defined scopes
     *
     * @param  string|array $scopes
     * @access public
     */
    public function __construct($scopes = '')
    {
        if ('' !== $scopes) {
            $this->scopes = (array) $scopes;
        }
    }

    /**
     * Override `getMatchedQueue()` in EventManager
     * {@inheritDoc}
     */
    protected function getMatchedQueue(
        /*# : string */ $eventName
    )/*# : EventQueueInterface */ {
        // get all shared managers
        $managers = $this->getShareables();

        // one new queue
        $nqueue = $this->newEventQueue();

        /* @var $mgr EventDispatcher */
        foreach ($managers as $mgr) {
            // find $eventName related names in $mgr
            $matchedNames = $mgr->globEventNames(
                $eventName, $mgr->getEventNames()
            );

            // combined event queue from each $mgr
            $nqueue = $nqueue->combine(
                $mgr->matchEventQueues($matchedNames)
            );
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
     * Get a merged queue in $this manager for event names provided
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
                $nqueue = $nqueue->combine($this->events[$evtName]);
            }
        }
        return $nqueue;
    }
}
