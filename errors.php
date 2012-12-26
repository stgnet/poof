<?php
    require 'poof.php';

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

    $content=uiDiv()->Add(uiHeading(3,"POOF Error Log"));

    foreach (arDir("errlog")->Match('*.txt')->SortNewest() as $errfile)
    {
        $text=file_get_contents($errfile);
        /*
        [message:protected] => MatchWhere field 'noexist' does not exist in record
        [string:Exception:private] => 
        [code:protected] => 0
        [file:protected] => /var/www/poof.stg.net/class/sierror.php
        [line:protected] => 181
        */

        $type='unknown';
        if (preg_match('_(\S+)\s+Object_',$text,$match) && !empty($match[1]))
            $type=$match[1];

        $message='unknown';
        if (preg_match('_\[message.*\] => (.*)\n_',$text,$match) && !empty($match[1]))
            $message=$match[1];

        $file='unknown';
        if (preg_match('_\[file.*\] => (.*)\n_',$text,$match) && !empty($match[1]))
            $file=$match[1];

        $line='unknown';
        if (preg_match('_\[line.*\] => (.*)\n_',$text,$match) && !empty($match[1]))
            $line=$match[1];

        $error="$type: $message in $file at $line";

        $p=uiParagraph($error);
        $p->Add(
            uiButton("Details",$errfile)->AddClass('btn-small')->NewTab(),
            uiButton("Delete")->AddClass('btn-small')
                ->Post('DeleteButton',array('file'=>$errfile))->Target($p)
        );
        $content->Add($p);
    }

    echo uiPage("POOF Error List")->Add(
        $content
    );
