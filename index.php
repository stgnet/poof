<?php
    require 'poof.php';

    // if this is the demo server, redirect to the demo
    if ($_SERVER['SERVER_NAME']=="poof.stg.net" && 
        empty($_SERVER['QUERY_STRING']) &&
        $_SERVER['REQUEST_METHOD']=='GET')
    {
        header("Location: http://poof.stg.net/demo.php");
        return;
    }

    header("Location: http://$POOF_HOST$POOF_URL/admin.php");
    return;
