<?php
    require 'navbar.inc';

    echo uiPage("POOF Demo")->Add(
        $navbar,
        uiContainer()->background("#efd")->Add(
            uiHeading("Demonstration of Icons"),
            uiParagraph()->Add(
                "Icon ",
                uiIcon('info-sign'),
                uiIcon('phone'),
                " Test"
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
