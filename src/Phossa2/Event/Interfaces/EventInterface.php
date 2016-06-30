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

use Phossa2\Event\Exception\InvalidArgumentException;

/**
 * EventInterface
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \ArrayAccess
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface EventInterface extends \ArrayAccess
{
    /**
     * Initialize this event
     *
     * @param  string $eventName
     * @param  object|string $context
     * @param  array $properties
     * @return $this
     * @throws InvalidArgumentException if arguments not right
     * @access public
     * @api
     */
    public function __invoke(
        /*# string */ $eventName,
        $context,
        array $properties = []
    );

    /**
     * Set event name
     *
     * @param  string $eventName event name
     * @return $this
     * @throws InvalidArgumentException if $eventName not right
     * @access public
     * @api
     */
    public function setName(/*# string */ $eventName);

    /**
     * Get event name
     *
     * @return string
     * @access public
     * @api
     */
    public function getName()/*# : string */;

    /**
     * Set event context, usually an object or static class name
     *
     * @param  object|string $context object or static classname
     * @return $this
     * @throws InvalidArgumentException if $context not right
     * @access public
     * @api
     */
    public function setContext($context);

    /**
     * Get event context, usually an object or static class name
     *
     * @return object|string
     * @access public
     * @api
     */
    public function getContext();

    /**
     * Has event property with $name
     *
     * Use this hasProperty() before getProperty() to avoid exception
     *
     * @param  string $name property name
     * @return bool
     * @access public
     * @api
     */
    public function hasProperty(/*# string */ $name)/*#: bool */;

    /**
     * Get event property with $name. Returns NULL if not found
     *
     * @param  string $name property name
     * @return mixed
     * @access public
     * @api
     */
    public function getProperty(/*# string */ $name);

    /**
     * Set the event property
     *
     * @param  string $name property name
     * @param  mixed $value property value
     * @return $this
     * @access public
     * @api
     */
    public function setProperty(/*# string */ $name, $value);

    /**
     * Get event's all properties in array
     *
     * @return array
     * @access public
     * @api
     */
    public function getProperties()/*# : array */;

    /**
     * Replace the event all properties
     *
     * @param  array $properties property array
     * @return $this
     * @access public
     * @api
     */
    public function setProperties(array $properties);

    /**
     * Add result from the current handler
     *
     * @param  mixed $result
     * @return $this
     * @access public
     * @api
     */
    public function addResult($result);

    /**
     * Get all the results
     *
     * @return array
     * @access public
     * @api
     */
    public function getResults()/*# : array */;

    /**
     * Stop event propagation
     *
     * @param  bool $stop stop propagation
     * @return $this
     * @access public
     * @api
     */
    public function stopPropagation(/*# bool */ $stop = true);

    /**
     * Is event propagation stopped
     *
     * @return bool
     * @access public
     * @api
     */
    public function isPropagationStopped()/*# : bool */;
}
