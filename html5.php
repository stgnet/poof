<?php
    // algamation of several html5 best practices examples in poof
    require 'poof.php';
    poof_theme('united');

    $navlist=array(
        'Link One'=>"#",
        'Link Two'=>"#",
        'Link Three'=>"#"
    );

    echo uiPage("HTML5 Best Practices")->Add(
        uiGoogleAnalytics('UA-34982565-1','poof.stg.net'),
        uiHeader('navbar')->Add(
            uiDiv('navbar-inner')->Add(
                uiHeading(4,"HTML5 Best Practices Example")->Left(),
                uiNavList($navlist)->Right()
            )
        ),
        uiDiv()->Add(
            uiSection()->Add(
                uiArticle()->Add(
                    uiHeader()->Add(
                        uiHGroup()->Add(
                            uiHeading(2,"Title"),
                            uiHeading(3,"by Author")
                        )
                    ),
                    uiParagraph("Article content...")
                )
            ),
            uiAside()->Add(
                uiList(array('First'=>"#",'Second'=>"#"))
            )
        ),
        uiFooter()->Add(
            uiParagraph("Footer")
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))
        )
    );

