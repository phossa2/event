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

use Phossa2\Shared\Shareable\Shareable;
use Phossa2\Event\Interfaces\NameGlobbingTrait;
use Phossa2\Event\Interfaces\EventManagerTrait;
use Phossa2\Event\Interfaces\ListenerAwareTrait;
use Phossa2\Event\Interfaces\EventQueueInterface;
use Phossa2\Event\Interfaces\ShareableManagerTrait;
use Phossa2\Event\Interfaces\EventManagerInterface;
use Phossa2\Event\Interfaces\ListenerAwareInterface;

/**
 * EventManager
 *
 * A basic event manager with
 *
 * - global & shared managers: global, for each class, interfaces etc.
 *
 * - simple event globbing: such as 'user.*'
 *
 * - listener aggregation: attach/detach listener support (aggregating)
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     Shareable
 * @see     EventManagerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EventManager extends Shareable implements EventManagerInterface, ListenerAwareInterface
{
    use EventManagerTrait,
        NameGlobbingTrait,
        ListenerAwareTrait,
        ShareableManagerTrait;

    /**
     * classes/interfaces with attached listeners
     *
     * @var    string[]
     * @access protected
     * @staticvar
     */
    protected static $classes = [];

    /**
     * {@inheritDoc}
     */
    public function __construct($scopes = '')
    {
        parent::__construct($scopes);

        // add class name as scope also
        $this->addScope($this->getClassName());
    }

    /**
     * Get a merged queue from related managers matching $eventName
     *
     * @param  string $eventName
     * @return EventQueueInterface
     * @access protected
     */
    protected function getMatchedQueue(
        /*# : string */ $eventName
    )/*# : EventQueueInterface */ {
        // get all shared managers
        $managers = $this->getShareables();

        $nqueue = $this->newEventQueue();

        /* @var $mgr EventManager */
        foreach ($managers as $mgr) {
            // find $eventName related names in $mgr
            $matchedNames = $mgr->globEventNames(
                $eventName, $mgr->getEventNames()
            );

            // combined event queue from each $mgr
            $nqueue = $nqueue->combine(
                $mgr->matchEventQueues($matchedNames)
            );
        }

        return $nqueue;
    }
}
