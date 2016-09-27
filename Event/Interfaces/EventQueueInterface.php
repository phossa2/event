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

use Phossa2\Shared\Queue\PriorityQueueInterface;

/**
 * EventQueueInterface
 *
 * We are not using SplPriorityQueue because it has bug in HHVM env.
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     PriorityQueueInterface
 * @version 2.1.0
 * @since   2.0.0 added
 * @since   2.1.0 changed priority
 * @since   2.1.2 now extending PriorityQueueInterface
 */
interface EventQueueInterface extends PriorityQueueInterface
{
}
