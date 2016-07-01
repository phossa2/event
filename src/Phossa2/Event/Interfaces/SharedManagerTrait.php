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

use Phossa2\Shared\Shareable\ShareableTrait;

/**
 * SharedManagerTrait
 *
 * Support for shared managers using ShareableTrait
 *
 * ```php
 * // one event manager instance
 * $event_dispatcher = new EventDispatcher();
 *
 * // global event manager, default scope is ''
 * $globalManager = EventDispatcher::getShareable();
 *
 * // shared manager for a scope, say 'MVC'
 * $MvcManager = EventDispatcher::getShareable('MVC');
 *
 * // class/interface level shared manager
 * $classManager = EventDispatcher::getShareable('Phossa\\Config\\Config');
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     SharedManagerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait SharedManagerTrait
{
    use ShareableTrait;

    /**
     * {@inheritDoc}
     */
    public static function onEvent(
        $scope,
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        foreach ((array) $scope as $sc) {
            /* @var $em EventManagerInterface */
            $em = static::getShareable($sc);
            $em->on($eventName, $callable, $priority);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function offEvent(
        $scope,
        /*# string */ $eventName,
        callable $callable = null
    ) {
        foreach ((array) $scope as $sc) {
            /* @var $em EventManagerInterface */
            $em = static::getShareable($sc);
            $em->off($eventName, $callable);
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function onGlobalEvent(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        static::onEvent('', $eventName, $callable, $priority);
    }

    /**
     * {@inheritDoc}
     */
    public static function offGlobalEvent(
        /*# string */ $eventName,
        callable $callable = null
    ) {
        static::offEvent('', $eventName, $callable);
    }

    /**
     * Override `getOwnScopes()` in ShareableTrait
     *
     * {@inheritDoc}
     */
    protected function getOwnScopes()/*# : array */
    {
        // result
        $result = [];

        // all scopes avaible
        $allScopes = static::getScopes();

        // loop thru own scopes
        foreach ($this->scopes as $scope) {
            $result[$scope] = true;
            foreach ($allScopes as $class) {
                if ($this->isSubType($scope, $class)) {
                    $result[$class] = true;
                }
            }
        }

        // alway add global scope
        $result[''] = true;

        return array_keys($result);
    }

    /**
     * Is $type a classname or interface name ?
     *
     * @param  string $type
     * @return bool
     * @access protected
     */
    protected function isAType(/*# string */ $type)/*# : bool */
    {
        return class_exists($type) || interface_exists($type);
    }

    /**
     * is $childType child type of $parentType
     *
     * @param  string $childType
     * @param  string $parentType
     * @return bool
     * @access protected
     */
    protected function isSubType(
        /*# string */ $childType,
        /*# string */ $parentType
    )/*# : bool */ {
        return
            $this->isAType($childType) &&
            $this->isAType($parentType) &&
            is_a($childType, $parentType, true);
    }
}
