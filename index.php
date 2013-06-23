<?php
    require 'poof.php';

    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/demo.php');

    echo "<pre>".print_r($_SERVER,true);

