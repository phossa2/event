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
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface EventCapableInterface
{
    /**
     * Setup event related stuff
     *
     * ```php
     * $this->setEventManager(
     *     new EventManager(),
     *     new MyEvent('prototype')
     * );
     * ```
     *
     * @param  EventManagerInterface $eventManager
     * @param  EventInterface $eventPrototype
     * @return $this
     * @access public
     * @api
     */
    public function setEventManager(
        EventManagerInterface $eventManager,
        EventInterface $eventPrototype = null
    );

    /**
     * Get the event manager, if not set will create one
     *
     * @return EventManagerInterface
     * @access public
     * @api
     */
    public function getEventManager()/*# : EventManagerInterface */;

    /**
     * Trigger an event and processed it by event manager, return the event
     *
     * @param  string $eventName event name
     * @param  array $properties (optional) event property array
     * @return EventInterface
     * @throws \Exception if event processing goes wrong
     * @access public
     * @api
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */;
}
