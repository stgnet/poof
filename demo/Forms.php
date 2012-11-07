<?php
    require '../poof.php';
    require 'navbar.inc';

    $login=array(
        'username'=>array('type'=>"text",'desc'=>"Email"),
        'password'=>array('type'=>"password",'desc'=>"Password"),
        'remember'=>array('type'=>"checkbox",'desc'=>"Remember Me"),
        'submit'=>array('type'=>"button",'value'=>"Sign In")
    );
    $search=array(
        'search'=>array('type'=>"text",'class'=>"search-query",'desc'=>"Enter Query",
            'options'=>array("CSS","HTML","PHP","jQuery","MySQL","Bootstrap")),
        'submit'=>array('type'=>"button",'value'=>"Search")
    );
    $misc=array(
        'one'=>array('type'=>"checkbox",'desc'=>"One"),
        'two'=>array('type'=>"checkbox",'desc'=>"Two"),
        'three'=>array('type'=>"checkbox",'desc'=>"Three"),
        'radio'=>array('type'=>"radio",'desc'=>"Select one",
            'options'=>array(
                'alpha'=>"Alpha",
                'beta'=>"Beta",
                'gamma'=>"Gamma",
            ),
        ),
        'compass'=>array('type'=>"select",'desc'=>"Choose direction",
            'options'=>array(
                'N'=>"North",
                'S'=>"South",
                'E'=>"East",
                'W'=>"West"
            )
        ),
        'submit'=>array('type'=>"button",'value'=>"Save"),
    );
    $target=uiDiv()->Add("Post data will appear here");
    $postfunc=function($data) {
        $_SESSION['demo_form']=$data;
        echo uiPre(print_r($data,true))->Background("#ff8");
    };

    if (empty($_SESSION['demo_form']))
        $data=array();
    else
        $data=$_SESSION['demo_form'];

    $tabs=array(
        'Default'=>uiForm($login,$data)->OnSubmit($target)->Post($postfunc),
        'Inline'=>uiForm($login,false,"inline")->OnSubmit($target)->Post($postfunc),
        'Horizontal'=>uiForm($login,false,"horizontal")->OnSubmit($target)->Post($postfunc),
        'Search'=>uiForm($search,false,"search")->OnSubmit($target)->Post($postfunc),
        'Misc'=>uiForm($misc,false,"horizontal")->OnSubmit($target)->Post($postfunc)
    );

    echo uiPage("POOF Demo")->Background("#def")->Add(
        $navbar,
        uiContainer()->Background("#efd")->Add(
            uiRow()->Add(
                uiSpan(8)->Add(
                    uiHeading("Form Demonstration"),
                    uiTabbable($tabs)
                ),
                uiSpan(4)->Background("#fff")->Add(
                    uiParagraph()->Add("&nbsp;"),
                    uiWell()->Add($target)
                )
            )
        ),
        uiContainer()->Add(
            uiHeading(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        ),
        uiParagraph(),
        uiContainer()->Add(
            uiPre(session_id()),
            uiDebug("_SESSION")
        )
    );
