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

use Phossa2\Shared\Shareable\ShareableInterface;

/**
 * SharedManagerInterface
 *
 * Support for shared event manager for different 'scope'. Default scope
 * is global which is ''. Common usage is to use class name or interface
 * name as scope.
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface SharedManagerInterface extends ShareableInterface, EventManagerInterface
{
    /**
     * Bind a callable to event name in scope $scope
     *
     * ```php
     * eventDispatcher::onEvent(
     *     'Phossa\\Mvc\\MvcInterface',
     *     'mvc.onRoute',
     *     function ($evt) {
     *         // ...
     *     }
     * );
     * ```
     *
     * @param  string|string[] $scope
     * @param  string $eventName
     * @param  callable $callable
     * @param  int $priority
     * @access public
     * @static
     * @api
     */
    public static function onEvent(
        $scope,
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Unbind a callable from a specific eventName in $scope
     *
     * @param  string|string[] $scope
     * @param  string $eventName
     * @param  callable $callable
     * @access public
     * @static
     * @api
     */
    public static function offEvent(
        $scope,
        /*# string */ $eventName = '',
        callable $callable = null
    );

    /**
     * Bind a callable to event name in the global scope
     *
     * Alias of `::onEvent('', $eventName, $callable, $priority)`
     *
     * @param  string $eventName
     * @param  callable $callable
     * @param  int $priority
     * @access public
     * @static
     * @api
     */
    public static function onGlobalEvent(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Unbind a callable from a specific eventName in the global scope
     *
     * Alias of `::offEvent('', $eventName, $callable)`
     *
     * @param  string $eventName
     * @param  callable $callable
     * @access public
     * @static
     * @api
     */
    public static function offGlobalEvent(
        /*# string */ $eventName = '',
        callable $callable = null
    );
}
