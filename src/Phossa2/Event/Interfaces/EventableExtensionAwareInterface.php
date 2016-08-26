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

use Phossa2\Shared\Extension\ExtensionInterface;
use Phossa2\Shared\Extension\ExtensionAwareInterface;

/**
 * EventableExtensionAwareInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.6
 * @since   2.1.6 added
 */
interface EventableExtensionAwareInterface extends ExtensionAwareInterface, EventCapableInterface, ListenerInterface
{
    /**
     * Register a callable or ExtensionInterface with event
     *
     * @param  callable|ExtensionInterface $extension
     * @param  string $eventName
     * @param  int $priority
     * @return $this
     * @access public
     * @api
     */
    public function addExt(
        $extension,
        /*# string */ $eventName = '*',
        /*# int */ $priority = 0
    );
}
