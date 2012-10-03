<?php

// poof generic base class
class pfBase 
{
    public function __construct()
    {
    }

    // fix ability to call closures
    public function __call($method,$args)
    {
        if ($this->{$method} instanceof Closure)
            return call_user_func_array($this->{$method},$args);

        $name=get_class($this);
        Fatal("Class '$name' does not have method '$method'");
    }
}
