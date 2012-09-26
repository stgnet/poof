<?php
    require 'poof.php';

    if (!empty($_GET['file'])) {
        $file=$_GET['file'];
        echo uiPage("Code Editor - $file")->Add(
            uiContainer()->Add(
                uiHeader("Code Editor - $file")
            ),
            uiContainer()->Add(
                uiForm()->Add(
                    uiCodeMirror(file_get_contents($file))
                )
            )
        );

        return;
    }

    $navs=array();
    foreach (arDir() as $file)
        $navs[$file]="editor.php?file=$file";

    echo uiPage("Code Editor - File List")->Add(
        uiContainer()->Add(
            uiHeader("Code Editor")
        ),
        uiNavbar()->Add(
            uiNavlist($navs)
        ),
        uiContainer()->Add(
            uiDebug()
        )
    );

    return;

    echo uiPage("Code Editor")->Add(
        uiContainer("navbar")->Add(
            uiContainer("navbar-inner")->AddStyle("background: #fed;")->Add(
                uiImage("img/poof.png","index.php")->AddClass("nav"),
                uiNavList($navmenu)->AddClass("pull-right")
            )
        ),
        uiContainer()->Add(
            uiHero()->AddStyle("background: #efd;")->Add(
                uiCarousel($carousel)->AddClass("pull-right"),
                uiHeader("Hello, World!"),
                uiParagraph("This is a demostration of")->Add(
                    uiTooltip("Programmatic Object-oriented Orthogonal Framework")->Add(
                        "POOF"
                    )
                ),
                uiDropdown("Download Code")->AddClass("btn")->Add(
                        uiLink("http://github.com/stgnet/poof","GitHub"),
                        //uiLink("http://somewhere","Download")
                        uiTooltip("Not currently available")->Add("Download")
                )
            )
        ),
        uiContainer()->Add(
            uiHeader(3,"The PHP code that generated this page:"),
            uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
