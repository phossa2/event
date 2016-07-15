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
use Phossa2\Event\Interfaces\EventQueueInterface;

/**
 * EventQueue
 *
 * One implementation of EventQueueInterface
 *
 * ```php
 * // create a new queue
 * $queue = new EventQueue();
 *
 * // insert a callable
 * $queue->insert($callable, 50);
 *
 * // count handlers in the queue
 * if (count($queue) > 0) {
 *     // loop thru the queue
 *     foreach ($queue as $q) {
 *         $callable = $q['data'];
 *         $priority = $q['priority'];
 *     }
 * }
 *
 * // remove callable
 * $queue->remove($callable);
 *
 * // merge with another queue
 * $nqueue = $queue->combine($another_queue);
 *
 * // flush (empty)
 * $queue->flush();
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventQueueInterface
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 used updated interface
 */
class EventQueue extends ObjectAbstract implements EventQueueInterface
{
    /**
     * inner data storage
     *
     * @var    array
     * @access protected
     */
    protected $queue;

    /**
     * marker for sorted queue
     *
     * @var    bool
     * @access protected
     */
    protected $sorted = false;

    /**
     * priority counter, descreasing
     *
     * @var    int
     * @access protected
     */
    protected $counter = 10000000;

    /**
     * constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function insert(callable $callable, /*# int */ $priority = 0)
    {
        // fix priority
        $pri = $this->fixPriority((int) $priority);

        // generate key to be used (int)
        $key = $this->generateKey($pri);

        // make sure not duplicated
        $this->remove($callable);

        // added to the queue
        $this->queue[$key] = ['data' => $callable, 'priority' => $pri];

        // mark as not sorted
        $this->sorted = false;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(callable $callable)
    {
        foreach ($this->queue as $key => $val) {
            if ($val['data'] === $callable) {
                unset($this->queue[$key]);
                break;
            }
        }
        return $this;
    }

    /**
     * {@inheritdic}
     */
    public function flush()
    {
        $this->queue = [];
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function combine(
        EventQueueInterface $queue
    )/*# : EventQueueInterface */ {
        // clone a new queue
        $nqueue = clone $this;

        // insert into new queue
        foreach ($queue as $data) {
            $nqueue->insert($data['data'], $data['priority']);
        }

        return $nqueue;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->queue);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        // sort queue if not yet
        $this->sortQueue();

        // return iterator
        return new \ArrayIterator($this->queue);
    }

    /**
     * Make sure priority in the range of -100 - +100
     *
     * @param  int $priority
     * @return int
     * @access protected
     */
    protected function fixPriority(/*# int */ $priority)/*# : int */
    {
        return (int) ($priority > 100 ? 100 : ($priority < -100 ? -100 : $priority));
    }

    /**
     * Generate one int base on the priority
     *
     * @param  int $priority
     * @return int
     * @access protected
     */
    protected function generateKey(/*# int */ $priority)/*# : int */
    {
        return ($priority + 100) * 10000000 + --$this->counter;
    }

    /**
     * Sort the queue from higher to lower int $key
     *
     * @return $this
     * @access protected
     */
    protected function sortQueue()
    {
        if (!$this->sorted) {
            krsort($this->queue);
            $this->sorted = true;
        }
        return $this;
    }
}
