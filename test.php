<?php
    // load base library
    require 'poof.php';
    siDiscern();

    global $POOF_UI_DEBUG;
    if (!empty($_GET['debug']))
        $POOF_UI_DEBUG=true;

    //auDigest('TEST',array('admin'=>'secret'));


    siDiscern()->Event("build");
    echo uiPage("POOF")->Add(
        uiHeader("POOF Diagnostic Tool")->Add(
            uiDebug('POOF_DIR'),
            uiDebug('POOF_URL')
        ),
        uiDebug('_SESSION'),
        uiDebug('_SERVER'),
        uiDebug('_GET'),
        uiDebug('_POST')
    );

    siDiscern()->Event("done");

