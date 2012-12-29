<?php
    require 'poof.php';

    if ($file=safe($_GET['file']))
    {
        if (substr($file,0,8)!="discern/")
            $file="discern/".$file;

        echo "<pre>";
        echo file_get_contents($file);
        return;
    }

    // post func is global to avoid error due to elements being deleted
    function DeleteButton($data)
    {
        $file=$data['file'];
        if (file_exists($file) && unlink($file))
            echo uiAlert('success',"Deleted")->RemoveAfter(3);
        else
            echo uiAlert('error',"file not found");
        return(true);
    };

    $content=uiDiv()->Add(uiHeading(3,"POOF Discern Log"));

    foreach (arDir("discern")->Match('*.csv')->SortNewest() as $discern)
    {
        $url=$_SERVER['PHP_SELF']."?file=".urlencode($discern);
        $p=uiParagraph($discern);
        $p->Add(
            uiButton("Details",$url)->AddClass('btn-small')->NewTab(),
            uiButton("Delete")->AddClass('btn-small')
                ->Post('DeleteButton',array('file'=>$discern))->Target($p)
        );
        $content->Add($p);
    }

    echo uiPage("POOF Discern Log")->Add(
        $content
    );
