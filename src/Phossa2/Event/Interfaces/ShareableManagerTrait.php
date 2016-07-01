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
use Phossa2\Shared\Shareable\ShareableInterface;

/**
 * ShareableManagerTrait
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
 * @see     ShareableInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait ShareableManagerTrait
{
    use ShareableTrait;

    /**
     * classes/interfaces with attached listeners
     *
     * @var    string[]
     * @access protected
     * @staticvar
     */
    protected static $classes = [];

    /**
     * Override `getScopes()` in ShareableTrait
     *
     * {@inheritDoc}
     */
    protected function getScopes()/*# : array */
    {
        $scopes = $this->scopes;
        $result = [];

        // matching classes/interfaces
        array_map(function($cls) use ($scopes, &$result) {
            foreach ($scopes as $scope) {
                $result[$scope] = true;
                if (is_a($scope, $cls, true)) {
                    $result[$cls] = true;
                }
            }
        }, self::$classes);

            // alway add global scope
            $result[''] = true;

            return array_keys($result);
    }
}
