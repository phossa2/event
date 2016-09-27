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
use Phossa2\Event\Traits\EventCapableTrait;
use Phossa2\Event\Traits\EventableExtensionAwareTrait;
use Phossa2\Event\Interfaces\EventableExtensionAwareInterface;

/**
 * EventableExtensionCapableAbstract
 *
 * - able to trigger events (EventCapableInterface)
 * - able to dealing with extensions (ExtensionAwareInterface)
 * - able to listen to events (ListenerInterface)
 * - able to dealing with eventable extensions (EventableExtensionAwareInterface)
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventableExtensionAwareInterface
 * @version 2.1.6
 * @since   2.1.6 added
 */
abstract class EventableExtensionCapableAbstract extends ObjectAbstract implements EventableExtensionAwareInterface
{
    use EventCapableTrait, EventableExtensionAwareTrait;
}
