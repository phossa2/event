# phossa2/event [ABANDONED PLEASE USE  phoole/event library instead]
[![Build Status](https://travis-ci.org/phossa2/event.svg?branch=master)](https://travis-ci.org/phossa2/event)
[![Code Quality](https://scrutinizer-ci.com/g/phossa2/event/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phossa2/event/)
[![Code Climate](https://codeclimate.com/github/phossa2/event/badges/gpa.svg)](https://codeclimate.com/github/phossa2/event)
[![PHP 7 ready](http://php7ready.timesplinter.ch/phossa2/event/master/badge.svg)](https://travis-ci.org/phossa2/event)
[![HHVM](https://img.shields.io/hhvm/phossa2/event.svg?style=flat)](http://hhvm.h4cc.de/package/phossa2/event)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa2/event.svg?style=flat)](https://packagist.org/packages/phossa2/event)
[![License](https://img.shields.io/:license-mit-blue.svg)](http://mit-license.org/)

**phossa2/event** is a PSR-14 event manager library for PHP.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with
[PSR-1][PSR-1], [PSR-2][PSR-2], [PSR-4][PSR-4] and the upcoming [PSR-14][PSR-14]

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[PSR-14]: https://github.com/php-fig/fig-standards/blob/master/proposed/event-manager.md "Event Manager"

Installation
---
Install via the `composer` utility.

```
composer require "phossa2/event=2.1.*"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/event": "^2.1.0"
    }
}
```

Features
---

- Event name [globbing](#glob).

- Built-in *multiple* [shared event managers](#shared) support.

- [Attach and detach](#attach) listeners.

- [Static event manager](#static) support.

- Built-in [class level](#class) events support.

- Able to [limit](#limit) number of times of an event callable executed.

Usage
---

- <a name="start"></a>Quick start

  ```php
  use Phossa2\Event\EventDispatcher;

  // event dispatcher
  $events = new EventDispatcher();

  // bind event with a callback
  $events->attach('login.success', function($evt) {
      echo "logged in as ". $evt->getParam('username');
  });

  // bind event with a callable
  $events->attach('login.attempt', [$logger, 'logEvent']);

  // unbind an event
  $events->clearListeners('login.attempt');

  // fire the trigger
  $events->trigger('login.success');
  ```

- <a name="glob"></a>Event name globbing

  Event name globbing means callables of the binding 'login.*' will also be
  triggered when triggering event 'login.success'.

  ```php
  // bind 'login.*' with callables
  $events->attach('login.*', function($evt) {
      echo $evt->getName();
  });

  // trigger 'login.atttempt' will also trigger callables of 'login.*'
  $events->trigger('login.attempt');
  ```

  The globbing rules are similiar to the PHP function `glob()`, where

  - `*` in the string means any chars except the dot.

  - If `*` at the end, will match any chars including the dot. e.g. `login.*`
    will match 'login.attempt.before'.

  - `.` means the dot.

  - one-char-string `*` means match any string (including the dot).

  **Note:** Name globbing **ONLY** happens when event is being triggered.
  Binding or unbinding events only affect the *EXACT* event name.

  ```php
  // unbind the exact 'login.*'
  $events->clearListeners('login.*');
  ```

- <a name="shared"></a>Shared event manager support

  Class `EventDispatcher` implements the `Phossa2\Shared\Shareable\ShareableInterface`.

  `ShareableInterface` is an extended version of singleton pattern. Instead of
  supporting only one shared instance, Classes implements `ShareableInterface`
  may have shared instance for different `scope`.

  ```php
  // global event manager, global scope is ''
  $globalEvents = EventDispatcher::getShareable();

  // shared event manager in scope 'MVC'
  $mvcEvents = EventDispatcher::getShareable('MVC');

  // an event manager instance, which has scope 'MVC'
  $events = new EventDispatcher('MVC');

  // in scope MVC ?
  var_dump($events->hasScope('MVC')); // true

  // in global scope ?
  var_dump($events->hasScope()); // true
  ```

  Callables bound to a shared manager will also be triggered if an event manager
  instance has the same scope.

  ```php
  // shared event manager in scope 'MVC'
  $mvcEvents = EventDispatcher::getShareable('MVC');

  // bind with pirority 100 (highest priority)
  $mvcEvents->attach('*', function($evt) {
      echo "mvc";
  }, 100);

  // create a new instance within the MVC scope
  $events = new EventDispatcher('MVC');

  // bind with default priority 0
  $events->attach('test', function($evt) {
      echo "test";
  });

  // will also trigger matched events in $mvcEvents
  $events->trigger("test");
  ```

  Event manager instance can have multiple scopes, either specified during the
  instantiation or using `addScope()`.

  ```php
  // create an event manager with 2 scopes
  $events = new EventDispatcher(['MVC', 'AnotherScope']);

  // add another scope
  $events->addScope('thirdScope');
  ```

  Couple of helper methods are provided for on/off/trigger events with shared
  managers.

  ```php
  // bind a callable to global event manager
  EventDispatcher::onGlobalEvent('login.success', function() {});

  // use interface name as a scope
  EventDispatcher::onEvent(
      'Psr\\Log\\LoggerInterface', // scope
      'log.error', // event name
      function () {}
  );

  // unbind all callables of event 'log.error' in a scope
  EventDispatcher::offEvent(
      'Psr\\Log\\LoggerInterface',
      'log.error'
  );

  // unbind *ALL* events in global scope
  EventDispatcher::offGlobalEvent();
  ```

- <a name="attach"></a>Attaching a listener

  `Listener` implements the `ListenerInterface`. Or in short, provides a method
  `eventsListening()`.

  ```php
  use Phossa2\Event\Interfaces\ListenerInterface;

  class myListener implements ListenerInterface
  {
      public function eventsListening()
      {
          return [
              // one method of $this
              eventName1 => 'method1',

              // 2 methods
              eventName2 => ['callable1', 'method2'],

              // priority 20 and in a 'mvcScope' scope
              eventName2 => ['method2', 20, 'mvcScope'], // with priority 20

              eventName3 => [
                  ['method3', 50],
                  ['method4', 70, 'anotherScope']
              ]
          ];
      }
  }
  ```

  `EventDispatcher::attachListener()` can be used to bind events defined in
  `eventsListening()` instead of using `EventDispatcher::attach()` to bind each
  event manually.

  ```php
  $events = new EventDispatcher();

  $listener = new \myListener();

  // bind all events defined in $listener->eventsListening()
  $events->attachListener($listener);

  // will call $listener->method1()
  $events->trigger('eventName1');
  ```

- <a name="static"></a>Using event manager statically

  `StaticEventDispatcher` is a static wrapper for an `EventDispatcher` slave.

  ```php
  StaticEventDispatcher::attach('*', function($evt) {
      echo 'event ' . $evt->getName();
  });

  // will print 'event test'
  StaticEventDispatcher::trigger('test');
  ```

  `StaticEventDispatcher` is not the same as global event manager.
  `StaticEventDispatcher` has a default slave which is a shared event manager
  in scope `'__STATIC__'`. While global event manager is the shared event
  manager in global scope `''`.

  User may set another event manager to replace the default slave.

  ```php
  StaticEventDispatcher::setEventManager(new EventDispatcher());
  ```

- `EventCapableAbstract`

  `EventCapableAbstract` implements both `ListenerInterface` and
  `EventCapableInterface`. It will do the following when `triggerEvent()`
  is called,

  - Get the event manager. If it is not set yet, create one default event
    manager with current classname as scope.

  - Attach events defined in `eventsListening()` if not yet.

  - Trigger the event and processed by the event manager and all of the
    shared managers of its scopes.

  ```php
  class LoginController extends EventCapableAbstract
  {
      public function login() {

          // failed
          if (!$this->trigger('login.pre')) {
              return;
          }

          // ...
      }

      public function beforeLogin() {
          // ...
      }

      public function eventsListening()
      {
          return [
              'login.pre' => 'beforeLogin'
          ];
      }
  }
  ```

- `EventableExtensionAbstract` and `EventableExtensionCapableAbstract`

  `EventableExtensionCapableAbstract` is the base class supporting events and
  extensions.

  Detail usage can be found in [phossa2/cache](https://github.com/phossa2/cache)
  `Phossa2\Cache\CachePool` extends `EventableExtensionCapableAbstract` and
  `Phossa2\Cache\Extension\ByPass` extends `EventableExtensionAbstract`.

  Or look at [phossa2/route](https://github.com/phossa2/route).

- <a name="class"></a>Class or interface level events support

  Class or interface name can be used as the `scope`. When events bound to these
  kind of scopes, any events triggered by child class will also search callables
  defined in parent class/interface level shared event managers.

  ```php
  // define event '*' for interface 'MyInterface'
  EventDispatcher::onEvent(
      'MyInterface', '*', function() { echo "MyInterface"; }, 60
  );
  ```

  Extends `EventCapableAbstract`.

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

  // will trigger callable 'myMethod' and handlers for 'MyInterface'
  $obj->trigger('afterTest');
  ```

- <a name="limit"></a>Execute callable for limited times

  ```php
  // bind a callable for executing only once
  $events->one('user.login', function(Event $evt) {
      // ...
  });

  // 3 times
  $events->many(3, 'user.tag', function(Event $evt) {
      // ...
  });
  ```

Change log
---

Please see [CHANGELOG](CHANGELOG.md) from more information.

Testing
---

```bash
$ composer test
```

Contributing
---

Please see [CONTRIBUTE](CONTRIBUTE.md) for more information.

Dependencies
---

- PHP >= 5.4.0

- phossa2/shared >= 2.0.21

License
---

[MIT License](http://mit-license.org/)
