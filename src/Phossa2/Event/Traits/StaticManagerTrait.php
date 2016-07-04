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

use Phossa2\Event\Message\Message;
use Phossa2\Event\Exception\BadMethodCallException;

/**
 * StaticManagerTrait
 *
 * Able to use event manager statically by adding special scope '__STATIC__'
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait StaticManagerTrait
{
    /**
     * Provides a static interface for event dispatcher's dynamic methods
     *
     * @param  string $name method name
     * @param  array $arguments arguments
     * @return mixed
     * @access public
     * @static
     * @internal
     */
    public static function __callStatic($name, array $arguments)
    {
        // get a shared event manager in scope __STATIC__
        $mgr = static::getShareable('__STATIC__');

        if (method_exists($mgr, $name)) {
            return call_user_func_array([$mgr, $name], $arguments);
        }

        throw new BadMethodCallException(
            Message::get(
                Message::MSG_METHOD_NOTFOUND,
                $name,
                get_called_class()
            ),
            Message::MSG_METHOD_NOTFOUND
        );
    }
}
