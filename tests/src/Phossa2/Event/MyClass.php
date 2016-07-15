<?php

use Phossa2\Event\Event;
use Phossa2\Event\EventCapableAbstract;

interface MyInterface
{

}

class MyClass extends EventCapableAbstract implements MyInterface
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
