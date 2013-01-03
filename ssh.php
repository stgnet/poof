<?php

    require 'poof.php';

    if (!safe($_SESSION['POOFSITE']['login']))
        die(header("Location: http://$POOF_HOST$POOF_URL/admin.php"));

    foreach ($_SERVER as $var => $value)
        putenv("$var=$value");

    $fp=popen("shellinaboxd --cgi -t -s \"/:\$(id -u):\$(id -g):HOME:/bin/sh\" 2>&1","r");

    $valid=array('X-ShellInABox-Port','X-ShellInABox-Pid','Content-type');

    while ($line=trim(fgets($fp)))
    {
        if ($line=="") break;
        $exp=explode(':',$line,2);
        if (!in_array($exp[0],$valid))
            die("ERROR: ".$line);
        header($line);
    }
   
    //fpassthru($fp);
    while ($line=fgets($fp))
    {
        echo($line);
        if (trim($line)=="</html>") break;
    }
    
