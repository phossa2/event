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
use Phossa2\Event\Interfaces\EventQueueTrait;
use Phossa2\Event\Interfaces\EventQueueInterface;

/**
 * EventQueue
 *
 * ```php
 * $queue = new EventQueue();
 * $queue->insert($callable, 50);
 *
 * foreach ($queue as $q) {
 *     $call = $q['data'];
 *     $priority = $q['priority'];
 *     // ...
 * }
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventQueueInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EventQueue extends ObjectAbstract implements EventQueueInterface
{
    use EventQueueTrait;

    /**
     * constructor
     *
     * @access public
     * @api
     */
    public function __construct()
    {
        $this->flush();
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
}
