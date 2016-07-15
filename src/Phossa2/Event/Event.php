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
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Event\Interfaces\EventInterface;
use Phossa2\Event\Exception\InvalidArgumentException;

/**
 * Event
 *
 * Basic implementation of EventInterface with array access to parameters
 *
 * ```php
 * // create event
 * $evt = new Event(
 *     'login.attempt',         // event name
 *     $this,                   // event target
 *     ['username' => 'phossa'] // event parameters
 * );
 *
 * // get/set event parameter
 * if ('phossa' === $evt['username']) {
 *     $evt['username'] = 'phossa2';
 * }
 *
 * // stop event
 * $evt->stopPropagation();
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     EventInterface
 * @see     \ArrayAccess
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.1.0 using psr EventInterface now
 */
class Event extends ObjectAbstract implements EventInterface, \ArrayAccess
{
    /**
     * event name
     *
     * @var    string
     * @access protected
     */
    protected $name;

    /**
     * event target/context
     *
     * an object OR static class name (string)
     *
     * @var    object|string|null
     * @access protected
     */
    protected $target;

    /**
     * event parameters
     *
     * @var    array
     * @access protected
     */
    protected $parameters;

    /**
     * stop propagation
     *
     * @var    bool
     * @access protected
     */
    protected $stopped = false;

    /**
     * Constructor
     *
     * @param  string $eventName event name
     * @param  string|object|null $target event context, object or classname
     * @param  array $parameters (optional) event parameters
     * @throws InvalidArgumentException if event name is invalid
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $eventName,
        $target = null,
        array $parameters = []
    ) {
        $this->setName($eventName);
        $this->setTarget($target);
        $this->setParams($parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * {@inheritDoc}
     */
    public function getParams()
    {
        return $this->parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function getParam($name)
    {
        if (isset($this->parameters[(string) $name])) {
            return $this->parameters[(string) $name];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        if (!is_string($name) || empty($name)) {
            throw new InvalidArgumentException(
                Message::get(Message::EVT_NAME_INVALID, $name),
                Message::EVT_NAME_INVALID
            );
        }
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * {@inheritDoc}
     */
    public function setParams(array $params)
    {
        $this->parameters = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation($flag)
    {
        $this->stopped = (bool) $flag;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped()
    {
        return $this->stopped;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)/*# : bool */
    {
        return isset($this->parameters[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->getParam($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->parameters[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);
    }
}
