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
 * ListenerAwareInterface
 *
 * Able to process listener
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ListenerAwareInterface
{
    /**
     * Attach a listener with all its event handlers
     *
     * @param  ListenerInterface $listener
     * @return $this
     * @access public
     * @api
     */
    public function attachListener(ListenerInterface $listener);

    /**
     * Detach a listener with all its event handlers or one specific event
     *
     * @param  ListenerInterface $listener
     * @param  string $eventName
     * @return $this
     * @access public
     * @api
     */
    public function detachListener(
        ListenerInterface $listener,
        /*# string */ $eventName = ''
    );
}
