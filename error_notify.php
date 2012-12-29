<?php
    //require 'poof.php';

    if (empty($_GET['msg']))
        Fatal('No message supplied');
    $msg=$_GET['msg'];

    //$config=parse_ini_file('/etc/pooftalk.ini',true);

    /*
    pfGtalk()
        ->Login($config['login']['user'],$config['login']['pass'])
        ->To($config['notify']['user'])
        ->Send($_GET['msg']);
        */

    $ip=empty($_SERVER['REMOTE_ADDR'])?'0.0.0.0':$_SERVER['REMOTE_ADDR'];
    $host=gethostbyaddr($ip);
    $short=substr($msg,0,40);

    mail("scott@griepentrog.com","POOF ERROR $host ($ip) $short",$_GET['msg']);
