<?php
    require 'poof.php';

    $status=function($data)
    {
        echo "<pre>".htmlentities(`ps auwx`)."</pre>";
    };

    echo uiPage("Status")->Add(
        //uiContainer()->Add(
                uiLegend("Process list"),
                uiLongPoll()->Post($status)
        //)
    );

