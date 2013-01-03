<?php

require_once(dirname(dirname(__FILE__))."/poof.php");

class bad_daemon extends pfDaemonServer
{
    public function __construct()
    {
        echo $_SERVER['force_error'];
        parent::__construct('bad');
    }
    public function noop()
    {
        return(false);
    }
}

if (!empty($argv[1]) && $argv[1]=="-daemon") new bad_daemon();
