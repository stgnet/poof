<?php
    require 'poof/poof.php';
    poof_theme('cerulean');

    $config=dbPoofSite();

    $navmenu=array('Home'=>"demo.php");
    foreach (arDir("demo")->Match("*.php")->Sort() as $file)
        $navmenu[basename($file,".php")]=$file;

    $carousel=arDir("img")->Match("*/poof?.png");

    echo uiPage("POOF Demo")->Add(
        uiGoogleAnalytics('UA-34982565-1','poof.stg.net'),
        uiDiv("navbar")/*->AddClass("navbar-static-top navbar-inverse")*/->Add(
            // add uiContainer here to limit width of navbar
            uiDiv("navbar-inner")->Add(
                uiLink("#","POOF")->AddClass("brand"),
                //uiImage("img/poof.png","index.php")->AddClass("nav"),
                uiNavList($navmenu)->AddClass("pull-right")
            )
        ),
        uiContainer()->Add(
            uiHero()->Add(
                uiCarousel($carousel)->AddClass("pull-right"),
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
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))
        )
    );
