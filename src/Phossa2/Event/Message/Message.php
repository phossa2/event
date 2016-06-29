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

namespace Phossa2\Event\Message;

use Phossa2\Shared\Message\Message as BaseMessage;

/**
 * Mesage class for Phossa2\Event
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Message extends BaseMessage
{
    /*
     * Event name "%s" is not valid
     */
    const EVT_NAME_INVALID = 1606291014;

    /*
     * Event context "%s" is not valid
     */
    const EVT_CONTEXT_INVALID = 1606291015;

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::EVT_NAME_INVALID => 'Event name "%s" is not valid',
        self::EVT_CONTEXT_INVALID => 'Event context "%s" is not valid',
    ];
}
