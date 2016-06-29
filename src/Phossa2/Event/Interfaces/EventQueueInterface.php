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
 * EventQueueInterface
 *
 * We are not using SplPriorityQueue because it has bug in HHVM env.
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface EventQueueInterface extends \IteratorAggregate, \Countable
{
    /**
     * Insert event handler into queue with priority
     *
     * @param  callable $callable
     * @param  int $priority priority, 0 - 100, 0 executed first
     * @return $this
     * @access public
     * @api
     */
    public function insert(callable $callable, /*# int */ $priority = 50);

    /**
     * Remove an event handler from the queue
     *
     * @param  callable $callable
     * @return $this
     * @access public
     * @api
     */
    public function remove(callable $callable);

    /**
     * Empty/flush the queue
     *
     * @return $this
     * @access public
     * @api
     */
    public function flush();

    /**
     * Combine with another event queue and return the new queue
     *
     * return the result event queue, self queue is not changed
     *
     * @param  EventQueueInterface $queue
     * @return EventQueueInterface a new event queue
     * @access public
     * @api
     */
    public function combine(
        EventQueueInterface $queue
    )/*# : EventQueueInterface */;
}
