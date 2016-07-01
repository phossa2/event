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
 * Shareable stuff for event manager
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait ShareableManagerTrait
{
    /**
     * Override `getScopes()` in Shareable
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
