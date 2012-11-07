<?php
    require '../poof.php';
    require 'navbar.inc';

    echo uiPage("POOF Demo")->Background("#def")->Add(
        $navbar,
        uiContainer()->background("#efd")->Add(
            uiHeading("Demonstration of Tabs"),
            uiTabbable(array(
                "One"=>uiParagraph("This is the first tab"),
                "Two"=>uiParagraph("This is the second tab"),
                "Three"=>uiParagraph("This is the third tab")
                )
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
