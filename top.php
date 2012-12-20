<?php
    require 'poof.php';

    $status=function($data)
    {
        echo "<pre>".`top -b -n 1`."";
    };

    echo uiPage("Status")->Add(
        //uiContainer()->Add(
                uiLegend("Processes via TOP"),
                uiLongPoll()->Post($status)
        //)
    );

