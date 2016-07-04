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

use Phossa2\Event\Message\Message;
use Phossa2\Shared\Base\StaticAbstract;
use Phossa2\Event\Exception\BadMethodCallException;
use Phossa2\Event\Interfaces\EventManagerInterface;

/**
 * StaticEventDispatcher
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class StaticEventDispatcher extends StaticAbstract
{
    /**
     * slave event manager
     *
     * @var    EventManagerInterface[]
     * @access protected
     * @staticvar
     */
    protected static $event_manager = [];

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
        $mgr = static::getEventManager();
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

    /**
     * Set the inner event manager
     *
     * @param  EventManagerInterface $eventManager
     * @access public
     * @api
     * @static
     */
    public static function setEventManager(EventManagerInterface $eventManager)
    {
        self::$event_manager[get_called_class()] = $eventManager;
    }

    /**
     * Get the inner event manager
     *
     * @return EventManagerInterface $eventManager
     * @access public
     * @api
     * @static
     */
    public static function getEventManager()
    {
        if (!isset(self::$event_manager[get_called_class()])) {
            self::$event_manager[get_called_class()] =
                EventDispatcher::getShareable('__STATIC__');
        }
        return self::$event_manager[get_called_class()];
    }
}
