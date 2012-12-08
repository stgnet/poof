<?php
    require 'poof.php';

    function foobar()
    {
        throw new Exception('wrong side of bed');
    }

    if (php_sapi_name()!="cli") echo "<pre>";
    echo "Catching exception\n";

    try
    {
        foobar();
    }
    catch (Exception $e)
    {
        print_r($e);
    }

    echo "Uncaught exception\n";

    foobar();

