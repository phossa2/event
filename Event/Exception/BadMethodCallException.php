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

namespace Phossa2\Event\Exception;

/**
 * BadMethodCallException for Phossa2\Event
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \BadMethodCallException
 * @see     ExceptionInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface
{
}
