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

namespace Phossa2\Event\Traits;

use Phossa2\Event\Interfaces\ListenerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;

/**
 * ListenerAwareTrait
 *
 * Implementation of ListenerAwareInterface with scope (shared manager)
 * support.
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
     * {@inheritDoc}
     */
    public function attachListener(ListenerInterface $listener)
    {
        // get the standardized handlers of the $listener
        $events = $this->listenerEvents($listener);

        // add to manager's event pool
        foreach ($events as $handler) {
            if (null !== $handler[3]) {
                /* @var $em EventManagerInterface */
                $em = static::getShareable($handler[3]);
                $em->on($handler[0], $handler[1], $handler[2]);
            } else {
                $this->on($handler[0], $handler[1], $handler[2]);
            }
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
                $this->offListenerEvent($handler);
            }
        }

        return $this;
    }

    /**
     * standardize events definition
     *
     * @param  ListenerInterface $listener
     * @return array
     * @access protected
     */
    protected function listenerEvents(
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
     * standardize one 'method1' or ['method1', 20, $scope]
     * to [eventName, callable, priority, $scopeIfAny]
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
            $scope = isset($data[2]) ? $data[2] : null;
        } else {
            $callable = $this->expandCallable($listener, $data);
            $priority = 50;
            $scope = null;
        }
        return [$eventName, $callable, $priority, $scope];
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

    /**
     * off listener event [$eventName, $handler, $priority, $scope]
     *
     * @param  array $data
     * @access protected
     */
    protected function offListenerEvent(array $data)
    {
        // scope found
        if (null !== $data[3]) {
            /* @var $em EventManagerInterface */
            $em = static::getShareable($data[3]);
            $em->off($data[0], $data[1]);
        } else {
            $this->off($data[0], $data[1]);
        }
    }

    // from EventManagerInterface
    abstract public function on(/*# string */ $eventName, callable $callable, /*# int */ $priority = 50);
    abstract public function off(/*# string */ $eventName = '', callable $callable = null);
}
