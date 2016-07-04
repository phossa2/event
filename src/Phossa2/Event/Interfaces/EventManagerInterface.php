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
 * EventManagerInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface EventManagerInterface
{
    /**
     * Bind a callable to event name with priority 0 - 100 (100 executed last)
     *
     * e.g.
     * ```php
     * $events = new EventManager();
     *
     * // bind event 'user.login' to a callable
     * $events->on('user.login', function(Event $evt) {
     *     $user = $evt->getProperty('user');
     *     //...
     * });
     *
     * // trigger the 'user.login' event with some data
     * $events->triggerEvent(new Event('user.login', $this, ['user' => $user]));
     * ```
     *
     * @param  string $eventName
     * @param  callable $callable
     * @param  int $priority
     * @return $this
     * @access public
     * @api
     */
    public function on(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Unbind a callable from a specific eventName
     *
     * If $eventName == '', turn off all events
     *
     * @param  string $eventName
     * @param  callable $callable
     * @return $this
     * @access public
     * @api
     */
    public function off(
        /*# string */ $eventName = '',
        callable $callable = null
    );

    /**
     * trigger and process the event
     *
     * @param  EventInterface|string $event
     * @param  object|string $context
     * @param  array $properties
     * @return $this
     * @access public
     * @api
     */
    public function trigger($event, $context = null, array $properties = []);
}
