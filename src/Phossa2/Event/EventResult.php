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

/**
 * EventResult
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EventResult extends \SplStack
{
    /**
     * Get the first stored result
     *
     * @return null|mixed
     * @access public
     */
    public function first()
    {
        if (0 === count($this)) {
            return null;
        } else {
            return parent::bottom();
        }
    }

    /**
     * Get the last stored result
     *
     * @return NULL|mixed
     * @return value
     * @access public
     */
    public function last()
    {
        if (0 === count($this)) {
            return null;
        } else {
            return parent::top();
        }
    }
}
