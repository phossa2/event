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
 * CountableInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface CountableInterface
{
    /**
     * Bind a callable to event name and execute no more than that many times.
     *
     * @param  int $times execute how many times
     * @param  string $eventName event name
     * @param  callable $callable the callable
     * @param  int $priority
     * @return $this
     * @access public
     * @api
     */
    public function many(
        /*# int */ $times,
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Bind a callable to event name and execute at most one time
     *
     * Alias of `many(1, $eventName, $callable, $priority)`
     *
     * @param  string $eventName event name
     * @param  callable $callable the callable
     * @param  int $priority
     * @return $this
     * @access public
     * @api
     */
    public function one(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );
}
