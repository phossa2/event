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
 * EventResultInterface
 *
 * Dealing with result from listeners
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.0
 * @since   2.1.0 added
 */
interface EventResultInterface
{
    /**
     * Add result from the current handler
     *
     * @param  mixed $result
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
}
