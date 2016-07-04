# phossa2/event
[![Build Status](https://travis-ci.org/phossa2/event.svg?branch=master)](https://travis-ci.org/phossa2/event)
[![Code Quality](https://scrutinizer-ci.com/g/phossa2/event/badges/quality-score.png?b=master)](https://travis-ci.org/phossa2/event)
[![PHP 7 ready](http://php7ready.timesplinter.ch/phossa2/event/master/badge.svg)](https://travis-ci.org/phossa2/event)
[![HHVM](https://img.shields.io/hhvm/phossa2/event.svg?style=flat)](http://hhvm.h4cc.de/package/phossa2/event)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa2/event.svg?style=flat)](https://packagist.org/packages/phossa2/event)
[![License](https://poser.pugx.org/phossa2/event/license)](http://mit-license.org/)

**phossa2/event** is an event management library for PHP.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with
[PSR-1][PSR-1], [PSR-2][PSR-2], [PSR-4][PSR-4].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"

Installation
---
Install via the `composer` utility.

```
composer require "phossa2/event=2.*"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/event": "^2.0.0"
    }
}
```

Features
---

- Event name [globbing](#glob).

- Built-in [shared event manager](#shared) support.

- [Static event manager](#static) support.

- [Class level](#class) events support.


Usage
---

- <a name="start"></a>Quick start

  ```php
  use Phossa2\Event\EventDispatcher;

  // event dispatcher(manager)
  $events = new EventDispatcher();

  // bind event with a callable
  $events->on('login.success', function($evt) {
      echo "logged in as ". $evt->getProperty('username');
  });

  // unbind an event
  $events->off('login.attempt');

  // fire the trigger
  $events->trigger('login.success');
  ```

- <a name="glob"></a>Event name globbing

  Event name globbing means callables of the binding of 'login.*' will also be
  fired when event 'login.success' is being triggered.

  ```php
  // bind 'login.*' with callables
  $events->on('login.*', function($evt) {
      echo $evt->getName();
  });

  // trigger 'login.atttempt' will also trigger callables of 'loing.*'
  $events->trigger('login.attempt');
  ```

  The globbing rules are similiar to the PHP function `glob()`, where

  - `*` in the string means any chars except for the dot

  - `.` means the dot.

  - a single char `*` means match any string.

  **Note** Name globbing only happens when event is being triggered. Binding
  or unbinding events only affect the exact event name.

  ```php
  // unbind the exact 'login.*', not other login related events
  $events->off('login.*');
  ```

- <a name="shared"></a>Shared event manager support

  Class `EventDispatcher` has built-in support for shared event manager.

  ```php
  // '' is the global scope
  $globalEvents = EventDispatcher::getShareable();

  // another shared event manager in scope 'MVC'
  $mvcEvents = EventDispatcher::getShareable('MVC');

  // create a event manager instance in scope MVC
  $events = new EventDispatcher('MVC');

  // in scope MVC ?
  var_dump($events->hasScope('MVC')); // true

  // in global scope ?
  var_dump($events->hasScope()); // true
  ```

  Callables bound to a shared manager will also be triggered if an event manager
  instance falls in the same scope.

  ```php
  // bind with pirority 100 (last executed)
  $mvcEvents->on('*', function($evt) {
      echo "mvc";
  }, 100);

  // create a new instance in the MVC scope
  $events = new EventDispatcher('MVC');

  // bind with default priority 50
  $events->on('test', function($evt) {
      echo "test";
  });

  // will also trigger matched events in $mvcEvents
  $events->trigger("test");
  ```

  Static methods are provided for on/off/trigger events with shared managers.

  ```php
  // bind to global scope
  EventDispatcher::onEvent('', 'login.success', function() {});

  // interface as the scope
  EventDispatcher::onEvent(
      'Psr\\Log\\LoggerInterface',
      'log.error',
      function () {}
  );

  // unbind events on global scope
  EventDispatcher::offEvent('');
  ```

- <a name="static"></a>Using event manager statically

  `StaticEventDispatcher` is the static wrapper for an `EventDispatcher`.

  ```php
  StaticEventDispatcher::on('*', function($evt) {
      echo 'event ' . $evt->getName();
  });

  // will print 'event test'
  StaticEventDispatcher::trigger('test');
  ```

  **Note** Do not confuse the global event manager with `StaticEventDispatcher`.

- <a name="class"></a>Class or interface level events support

  If using class or interface name as the scope, events can be bound to this
  specific class or interface.

  ```php
  // shared event manager for interface 'MyInterface'
  EventDispatcher::getShareable('MyInterface')->on('*', function() {
     echo "MyInterface";
  }, 60);
  ```

  `EventCapableAbstract` is a base class for any event capable classes.

  ```php
  class MyClass extends EventCapableAbstract implements MyInterface
  {
      public function myMethod()
      {
          echo "myMethod";
      }

      public function eventsListening()/*# : array */
      {
          return [
              // priority 20
              'afterTest' => ['myMethod', 20]
          ];
      }
  }

  $obj = new MyClass();

  // will print 'myMethodMyInterface'
  $obj->triggerEvent('afterTest');
  ```

APIs
---


Dependencies
---

- PHP >= 5.4.0

- phossa2/shared >= 2.0.12

License
---

[MIT License](http://mit-license.org/)
