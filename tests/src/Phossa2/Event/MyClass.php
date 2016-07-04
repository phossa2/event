<?php

use Phossa2\Event\EventCapableAbstract;

interface MyInterface
{

}

class MyClass extends EventCapableAbstract implements MyInterface
{
    public function myMethod()
    {
        echo "xxx";
    }

    public function eventsListening()/*# : array */
    {
        return [
            'afterTest' => 'myMethod'
        ];
    }
}
