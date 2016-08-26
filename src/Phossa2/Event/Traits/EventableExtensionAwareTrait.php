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

use Phossa2\Shared\Extension\ExtensionInterface;
use Phossa2\Shared\Extension\ExtensionAwareTrait;
use Phossa2\Event\Interfaces\EventableExtensionAwareInterface;

/**
 * EventableExtensionAwareTrait
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventableExtensionAwareInterface
 * @version 2.1.6
 * @since   2.1.6 added
 */
trait EventableExtensionAwareTrait
{
    use ExtensionAwareTrait, ListenerTrait;

    /**
     * {@inheritDoc}
     */
    public function addExt(
        $extension,
        /*# string */ $eventName = '*',
        /*# int */ $priority = 0
    ) {
        if (is_object($extension) && $extension instanceof ExtensionInterface) {
            $this->addExtension($extension);
        } else {
            $this->registerEvent($eventName, [$extension, $priority]);
        }
        return $this;
    }
}
