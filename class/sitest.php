<?php

class siTest extends pfSingleton
{
    public function __construct($value)
    {
        self::__invoke($value);
    }
    public function __invoke($value)
    {
        echo "siTest($value)\n";
    }

}
