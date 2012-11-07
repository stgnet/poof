<?php
    require '../poof.php';
    require 'navbar.inc';

    echo uiPage("POOF Demo")->background("#def")->Add(
        $navbar,
        uiContainer()->background("#efd")->Add(
            uiHeading("Demonstration of Tooltip"),
            uiParagraph()->Add(
                "Hover ",
                uiTooltip("Text to pop over")->Add("HERE"),
                " to trigger Tooltip"
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
