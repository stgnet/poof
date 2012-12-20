<?php
    require dirname(__FILE__).'/poof.php';

    poof_locate(false);

    echo "<pre>\n";
    echo "SCRIPT_NAME={$_SERVER['SCRIPT_NAME']}\n";
    echo "SCRIPT_FILENAME={$_SERVER['SCRIPT_FILENAME']}\n";
    echo "SERVER_HOME=".(empty($_SERVER['HOME'])?'':$_SERVER['HOME'])."\n";
    echo "POOF_FILE=$POOF_FILE\n";
    echo "POOF_DIR =$POOF_DIR\n";
    echo "POOF_ROOT=$POOF_ROOT\n";
    echo "POOF_URL =$POOF_URL\n";
    echo "POOF_HOST=$POOF_HOST\n";
    echo "POOF_CWD =$POOF_CWD\n";
    echo "POOF_PRJ =$POOF_PRJ\n";

?>
