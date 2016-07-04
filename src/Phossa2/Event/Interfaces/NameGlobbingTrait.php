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
 * Matching event name
 *
 * Returns matched names with $eventName. e.g.
 *
 * ```php
 * $names = ['*', 'u*.*', 'user.*', 'blog.*'];
 *
 * // returns ['*', 'u*.*', 'user.*']
 * $matched = $this->globEventNames('user.login', $names);
 * ```
 *
 * @package Phossa2\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait NameGlobbingTrait
{
    /**
     * Returns all names matches with $eventName
     *
     * e.g.
     * 'user.login' matches '*', 'u*.*', 'user.*', 'user.l*', 'user.login' etc.
     *
     * @param  string $eventName
     * @param  array $names
     * @return array
     * @access protected
     */
    protected function globEventNames(
        /*# string */ $eventName,
        array $names
    )/*# : array */ {
        $result = [];
        foreach ($names as $name) {
            if ($this->matchEventName($name, $eventName)) {
                $result[] = $name;
            }
        }
        return $result;
    }

    /**
     * Check to see if $name matches with $eventName
     *
     *  e.g.
     *  ```php
     *  // true
     *  $this->matchEventName('user.*', 'user.login');
     *
     *  // true
     *  $this->matchEventName('*', 'user.login');
     *
     *  // false
     *  $this->matchEventName('blog.*', 'user.login');
     *  ```
     *
     * @param  string $name
     * @param  string $eventName
     * @return bool
     * @access protected
     */
    protected function matchEventName(
        /*# string */ $name,
        /*# string */ $eventName
    )/*# : bool */ {
        if ('*' === $name || $name === $eventName) {
            return true;
        } elseif (false !== strpos($name, '*')) {
            $pat = str_replace(array('.', '*'), array('[.]', '[^.]*+'), $name);
            return (bool) preg_match('~^' . $pat . '$~', $eventName);
        } else {
            return false;
        }
    }
}
