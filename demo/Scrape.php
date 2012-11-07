<?php
    require "../poof.php";
    require "navbar.inc";

    $target=uiDiv();
    $postfunc=function($data)
    {
        $url=$data['URL'];
        if (!$url || substr($url,0,7)!="http://")
        {
            echo uiHeading(3,"Please enter a valid url")->Background("#f00");
            return;
        }
        $page=mlScrape($url);
        $tables=$page->ArrayOfTags("table");
        $output='';
        if (!count($tables))
            $output.="There are no tables on that page.  Please try another.";
        foreach ($tables as $table_element)
            $output.=print_r($table_element->ScrapeTable(),true);
        echo uiPre($output);
    };

    echo uiPage("POOF Demo")->Background("#def")->Add(
        $navbar,
        uiContainer()->Background("#efd")->Add(
            uiForm(
                array(
                    'URL'=>array('type'=>"text",'desc'=>"Enter URL to scrape"),
                    'submit'=>array('type'=>"button",'value'=>"Scrape")
                ),
                false,"inline"
            )->OnSubmit($target)->Post($postfunc)
        ),
        uiContainer()->Add($target),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
