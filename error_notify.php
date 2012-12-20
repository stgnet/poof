<?php
    require 'poof.php';

    if (empty($_GET['msg']))
        Fatal('No message supplied');

    $config=parse_ini_file('/etc/pooftalk.ini',true);

    pfGtalk()
        ->Login($config['login']['user'],$config['login']['pass'])
        ->To($config['notify']['user'])
        ->Send($_GET['msg']);
