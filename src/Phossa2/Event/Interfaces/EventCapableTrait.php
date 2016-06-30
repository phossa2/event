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

use Phossa2\Event\Event;
use Phossa2\Event\EventManager;

/**
 * Implementation of EventCapableInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventCapableInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait EventCapableTrait
{
    /**
     * event manager
     *
     * @var    EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * event prototype
     *
     * @var    EventInterface
     * @access protected
     */
    protected $event_proto;

    /**
     * {@inheritDoc}
     */
    public function setEventManager(
        EventManagerInterface $eventManager,
        EventInterface $eventPrototype = null
    ) {
        $this->event_manager = $eventManager;
        $this->event_proto = $eventPrototype;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()/*# : EventManagerInterface */
    {
        if (is_null($this->event_manager)) {
            $this->event_manager = new EventManager();
        }
        return $this->event_manager;
    }

    /**
     * {@inheritDoc}
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        // create an event
        $evt = $this->createEvent($eventName, $properties);

        // process it
        return $this->getEventManager()->triggerEvent($evt);
    }

    /**
     * Create an event with $eventName and properties
     *
     * @param  string $eventName
     * @param  array $properties
     * @return EventInterface
     * @access protected
     */
    protected function createEvent(
        /*# string */ $eventName,
        array $properties
    )/*# : EventInterface */ {
        // get event prototype
        if (is_null($this->event_proto)) {
            $this->event_proto = new Event($eventName, $this);
        }

        // clone event
        $evt = clone $this->event_proto;

        // init event
        return $evt($eventName, $this, $properties);
    }
}
