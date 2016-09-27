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

use Phossa2\Event\Event;
use Phossa2\Event\Interfaces\EventInterface;

/**
 * EventPrototypeTrait
 *
 * Injecting a event prototype for creating new ones
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.1
 * @since   2.1.1 added
 */
trait EventPrototypeTrait
{
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
    public function setEventPrototype(EventInterface $eventPrototype = null)
    {
        $this->event_proto = $eventPrototype;
        return $this;
    }

    /**
     * Create an event
     *
     * @param  string|EventInterface $event
     * @param  mixed $target
     * @param  array $parameters
     * @return EventInterface
     * @access protected
     */
    protected function newEvent(
        $event,
        $target = null,
        array $parameters = []
    )/*# : EventInterface */ {
        if (is_object($event)) {
            return $event;
        } elseif (is_null($this->event_proto)) {
            return new Event($event, $target, $parameters);
        } else {
            // clone the prototype
            $evt = clone $this->event_proto;
            $evt->setName($event);
            $evt->setTarget($target);
            $evt->setParams($parameters);
            return $evt;
        }
    }
}
