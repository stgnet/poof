<?php
    require 'poof.php';

    if (php_sapi_name()!="cli") echo "<pre>";
    echo "Warning error\n";

    Warning("this is a warning");

    echo "Fatal error\n";

    Fatal("this is fatal");

    echo "This should not be reached\n";


