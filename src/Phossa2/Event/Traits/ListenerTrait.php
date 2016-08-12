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

use Phossa2\Event\Interfaces\ListenerInterface;

/**
 * ListenerTrait
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ListenerInterface
 * @version 2.1.3
 * @since   2.1.3 added
 */
trait ListenerTrait
{
    /**
     * Events listening
     *
     * @var    array
     * @access protected
     */
    protected $events_listening = [];

    public function eventsListening()/*# : array */
    {
        return $this->events_listening;
    }

    /**
     * {@inheritDoc}
     */
    public function registerEvent(/*# string */ $eventName, $handler)
    {
        if (!isset($this->events_listening[$eventName])) {
            $this->events_listening[$eventName] = [];
        }
        $this->events_listening[$eventName][] = $handler;

        return $this;
    }
}
