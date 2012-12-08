<?php
    require 'poof/poof.php';

    poof_locate(false);

    echo "<pre>\n";
    echo "POOF_FILE=$POOF_FILE\n";
    echo "POOF_DIR =$POOF_DIR\n";
    echo "POOF_ROOT=$POOF_ROOT\n";
    echo "POOF_URL =$POOF_URL\n";
    echo "POOF_CWD =$POOF_CWD\n";
    echo "POOF_PRJ =$POOF_PRJ\n";

?>
