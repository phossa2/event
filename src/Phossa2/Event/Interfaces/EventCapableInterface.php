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
 * EventCapableInterface
 *
 * Classes implementing this interface is able to trigger events
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 updated
 */
interface EventCapableInterface
{
    /**
     * Setup event manager
     *
     * ```php
     * $this->setEventManager(new EventDispatcher());
     * ```
     *
     * @param  EventManagerInterface $eventManager
     * @return $this
     * @access public
     * @api
     */
    public function setEventManager(
        EventManagerInterface $eventManager
    );

    /**
     * Get the event manager. if not set yet, will CREATE ONE be default
     *
     * @return EventManagerInterface
     * @access public
     * @api
     */
    public function getEventManager()/*# : EventManagerInterface */;

    /**
     * Setup event prototype
     *
     * ```php
     * $this->setEventPrototype(
     *     new MyEvent('prototype')
     * );
     * ```
     *
     * @param  EventInterface $eventPrototype
     * @return $this
     * @access public
     * @api
     */
    public function setEventPrototype(EventInterface $eventPrototype);

    /**
     * Trigger an event and return the event status (isPropagationStopped)
     *
     * @param  string $eventName event name
     * @param  array $parameters (optional) custom event parameters if any
     * @return mixed last handler result
     * @throws \Exception if event processing goes wrong
     * @access public
     * @api
     */
    public function trigger(
        /*# string */ $eventName,
        array $parameters = []
    );

    /**
     * Trigger an event and processed it by event manager, return the event
     *
     * @param  string $eventName event name
     * @param  array $parameters (optional) custom event parameters if any
     * @return EventInterface
     * @throws \Exception if event processing goes wrong
     * @access public
     * @api
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $parameters = []
    )/*# : EventInterface */;
}
