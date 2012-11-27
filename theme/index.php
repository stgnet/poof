<?php
    require '../poof.php';

    if (!empty($_GET['theme']))
        poof_theme($_GET['theme']);

    $navmenu=array('Default'=>"index.php");
    foreach (arDir()->isDir()->Sort() as $theme)
        $navmenu[ucwords($theme)]="index.php?theme=$theme";

    echo uiPage("POOF Themes")->Add(
        uiGoogleAnalytics('UA-34982565-1','poof.stg.net'),
        uiDiv("navbar")->Add(
            // add uiContainer here to limit width of navbar
            uiDiv("navbar-inner")->Add(
                uiLink("#","POOF Themes")->AddClass("brand"),
                uiNavList($navmenu)->AddClass("pull-right")
            )
        ),
        uiContainer()->Add(
            uiHero()->Add(
                uiHeading("Hello, World!"),
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
            ),
            uiHeading("Section testing"),
            uiDiv()->Add(
                uiSection()->Add(
                    uiHeader()->Add(
                        uiDiv()->Right()->Add(
                            uiButton("Push Me")
                        )
                    ),
                    uiHeading(1,"Dashboard"),
                    uiDiv()->Add(
                        uiParagraph("Some paragraph text...")
                    )
                )
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))
        ),
        uiParagraph(),
        uiContainer()->Add(
            uiDebug("_SESSION")
        )
    );
