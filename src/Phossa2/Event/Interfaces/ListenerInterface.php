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
 * ListenerInterface
 *
 * Classes implementing this interface will be able to listen to events
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.3
 * @since   2.0.0 added
 * @since   2.1.3 added registerEvent()
 */
interface ListenerInterface
{
    /**
     * Get the list of events $this is listening
     *
     * e.g.
     * ```php
     * public function eventsListening()
     * {
     *     return [
     *         // one method of $this
     *         eventName1 => 'method1',
     *
     *         // 2 methods
     *         eventName2 => ['callable1', 'method2'],
     *
     *         // priority 20 and in a scope
     *         eventName2 => ['method2', 20, 'mvcScope'], // with priority 20
     *
     *         eventName3 => [
     *             'method1',
     *             ['method3', 50],
     *             ['method4', 70, 'anotherScope']
     *         ]
     *     ];
     * }
     * ```
     *
     * @return array
     * @access public
     * @api
     */
    public function eventsListening()/*# : array */;

    /**
     * Add events handler
     *
     * @param  string $eventName
     * @param  mixed $handler
     * @return $this
     * @since  2.1.3 added
     * @access public
     * @api
     */
    public function registerEvent(/*# string */ $eventName, $handler);
}
