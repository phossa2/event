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
 * Implementation of EventQueueInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventQueueInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait EventQueueTrait
{
    /**
     * inner data array
     *
     * @var    array
     * @access protected
     */
    protected $queue;

    /**
     * marker for sorted queue
     *
     * @var    bool
     * @access private
     */
    private $sorted = false;

    /**
     * We are not using complex priority like [ priority, PHP_MAX--]
     *
     * {@inheritDoc}
     */
    public function insert(callable $callable, /*# int */ $priority = 50)
    {
        // fix priority
        $pri = $this->fixPriority((int) $priority);

        // generate key (int)
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
     * Fix priority to 0 - 100
     *
     * @param  int $priority
     * @return int
     * @access protected
     */
    protected function fixPriority(/*# int */ $priority)/*# : int */
    {
        return $priority > 100 ? 100 : ($priority < 0 ? 0 : $priority);
    }

    /**
     * Generate key base on priority
     *
     * @param  int $priority
     * @return int
     * @access protected
     */
    protected function generateKey(/*# int */ $priority)/*# : int */
    {
        static $CNT = 0;
        return $priority * 10000000 + $CNT++;
    }

    /**
     * Sort the queue from lower to higher $key
     *
     * @return $this
     * @access protected
     */
    protected function sortQueue()
    {
        if (!$this->sorted) {
            ksort($this->queue);
            $this->sorted = true;
        }
        return $this;
    }
}
