<?php

use Phossa2\Event\Event;
use Phossa2\Event\EventCapableAbstract;

interface MyInterface
{
}

interface MyInterface2
{
}

class MyClass extends EventCapableAbstract implements MyInterface, MyInterface2
{
    public function myMethod()
    {
        echo "xxx";
        return true;
    }

    public function eventsListening()/*# : array */
    {
        return [
            'afterTest' => 'myMethod'
        ];
    }
}

class MyEvent extends Event
{
}
