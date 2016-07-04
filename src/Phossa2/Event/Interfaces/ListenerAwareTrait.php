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

/**
 * ListenerAwareTrait
 *
 * Implementation of ListenerAwareInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ListenerAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait ListenerAwareTrait
{
    /**
     * cache for listeners' event handlers
     *
     * @var    array
     * @access protected
     */
    protected $listener_cache = [];

    /**
     * {@inheritDoc}
     */
    public function attachListener(ListenerInterface $listener)
    {
        // get the standardized handlers of the $listener
        $events = $this->listenerEvents($listener);

        // add to manager's event pool
        foreach ($events as $handler) {
            $this->on($handler[0], $handler[1], $handler[2]);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detachListener(
        ListenerInterface $listener,
        /*# string */ $eventName = ''
    ) {
        // get the standardized handlers of the $listener
        $events = $this->listenerEvents($listener);

        // try find the match
        foreach ($events as $handler) {
            if ('' == $eventName || $handler[0] === $eventName) {
                $this->off($handler[0], $handler[1]);
            }
        }

        return $this;
    }

    /**
     * Get cached listener events (fixed already)
     *
     * @param  ListenerInterface $listener
     * @return array
     * @access protected
     */
    protected function listenerEvents(
        ListenerInterface $listener
    )/*# : array */ {
        $oid = spl_object_hash($listener);
        if (!isset($this->listener_cache[$oid])) {
            $this->listener_cache[$oid] = $this->fixListenerEvents($listener);
        }
        return $this->listener_cache[$oid];
    }

    /**
     * standardize events definition
     *
     * @param  ListenerInterface $listener
     * @return array
     * @access protected
     */
    protected function fixListenerEvents(
        ListenerInterface $listener
    )/*# : array */ {
        $result = [];
        foreach ($listener->eventsListening() as $eventName => $data) {
            $newData = $this->expandToHandler($data);
            foreach ($newData as $handler) {
                $result[] = $this->expandWithPriority(
                    $listener, $eventName, $handler
                );
            }
        }
        return $result;
    }

    /**
     * standardize to array of 'method1' or ['method1', 20]
     *
     * @param  mixed $data
     * @return array
     * @access protected
     */
    protected function expandToHandler($data)/*# : array */
    {
        if (is_callable($data)) {
            $result = [$data];
        } elseif (is_string($data)) {
            $result = [$data];
        } elseif (is_int($data[1])) {
            $result = [$data];
        } else {
            $result = $data;
        }
        return (array) $result;
    }

    /**
     * standardize one 'method1' or ['method1', 20]
     * to [eventName, callable, priority]
     *
     * @param  ListenerInterface $listener
     * @param  string $eventName
     * @param  mixed $data
     * @return array
     * @access protected
     */
    protected function expandWithPriority(
        ListenerInterface $listener,
        /*# string */ $eventName,
        $data
    )/*# : array */ {
        if (is_array($data) && is_int($data[1])) {
            $callable = $this->expandCallable($listener, $data[0]);
            $priority = $data[1];
        } else {
            $callable = $this->expandCallable($listener, $data);
            $priority = 50;
        }
        return [$eventName, $callable, $priority];
    }

    /**
     * standardize 'method' or callable to callable
     *
     * @param  ListenerInterface $listener
     * @param  mixed $callable
     * @return callable
     * @access protected
     */
    protected function expandCallable(
        ListenerInterface $listener,
        $callable
    )/*# : callable */ {
        if (is_callable($callable)) {
            return $callable;
        } else {
            return [$listener, $callable];
        }
    }

    // from other trait
    abstract public function on(/*# string */ $eventName, callable $callable, /*# int */ $priority = 50);
    abstract public function off(/*# string */ $eventName, callable $callable);
}
