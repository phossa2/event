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
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
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
     *         eventName1 => 'method1', // method1 of $this
     *         eventName2 => ['callable1, 'method2'],
     *         eventName2 => ['method2', 20], // with priority 20
     *         eventName3 => [
     *             ['method3', 50],
     *             ['method4', 70]
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
}
