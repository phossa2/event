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
use Phossa2\Event\Interfaces\EventTrait;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Interfaces\EventArrayAccessTrait;
use Phossa2\Event\Exception\InvalidArgumentException;

/**
 * Event
 *
 * Simple usage:
 *
 * ```php
 * // create event
 * $evt = new Event(
 *     'login.attempt',         // event name
 *     $this,                   // event context
 *     ['username' => 'phossa'] // event properties
 * );
 *
 * // get/set event property
 * if ('phossa' === $evt['username']) {
 *     $evt['username'] = 'phossa2';
 * }
 *
 * // stop event
 * $evt->stopPropagation();
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Event extends ObjectAbstract implements EventInterface
{
    use EventTrait, EventArrayAccessTrait;

    /**
     * Constructor
     *
     * @param  string $eventName event name
     * @param  string|object $context event context, object or static classname
     * @param  array $properties (optional) event properties
     * @throws InvalidArgumentException if arguments not right
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $eventName,
        $context,
        array $properties = []
    ) {
        $this->__invoke($eventName, $context, $properties);
    }
}
