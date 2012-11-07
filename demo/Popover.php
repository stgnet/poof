<?php
    require '../poof.php';
    require 'navbar.inc';

    echo uiPage("POOF Demo")->Background("#def")->Add(
        $navbar,
        uiContainer()->background("#efd")->Add(
            uiHeading("Demonstration of Popover"),
            uiParagraph()->Add(
                "Click ",
                uiPopover("Text to pop over")->Add("HERE"),
                " to trigger Popover"
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
