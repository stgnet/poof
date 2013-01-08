<?php
    require 'poof.php';

    echo "<pre>";
    echo poof_locate('poof.php')."\n";

    echo poof_locate('class/dbcsv.php')."\n";

    echo poof_locate('/www/poof.stg.net/tests/passhash.php')."\n";

    echo poof_url('/www/poof.stg.net/tests/passhash.php')."\n";


    print_r($GLOBALS);

