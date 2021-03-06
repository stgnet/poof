<?php
    require 'navbar.inc';

    echo uiPage("POOF Demo")->Add(
        $navbar,
        uiContainer()->Add(
            uiHeading("Demonstration of Collapsable sections"),
            uiCollapse(array(
                "One"=>uiParagraph("This is the first section"),
                "Two"=>uiParagraph("This is the second section"),
                "Three"=>uiParagraph("This is the third section")
                )
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
