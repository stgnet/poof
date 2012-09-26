<?php
    require '../poof.php';
    require 'navbar.inc';

    echo uiPage("POOF Demo")->AddStyle("background: #def;")->Add(
        $navbar,
        uiContainer()->AddStyle("background: #efd;")->Add(
            uiHeader("Demonstration of Tabs"),
            uiTabbable(array(
                "One"=>uiParagraph("This is the first tab"),
                "Two"=>uiParagraph("This is the second tab"),
                "Three"=>uiParagraph("This is the third tab")
                )
            )
        ),
        uiContainer()->Add(
            uiHeader(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
