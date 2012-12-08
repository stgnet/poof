<?php
    require 'poof.php';

    echo "<pre>\n";

    echo "This program demonstrates how a syntax error is handled\n";

    $file='syntax-temp.php';

    file_put_contents($file,'<'."?php
    echo basename('testfile.php')
    echo \"\n\";
    ");

    require $file;

    echo "Should not reach this point\n";
