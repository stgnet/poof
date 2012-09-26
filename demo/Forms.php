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
        'search'=>array('type'=>"text",'class'=>"search-query",'desc'=>"Enter Query"),
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
        'cancel'=>array('type'=>"cancel",'value'=>"Cancel")
    );

    echo uiPage("POOF Demo")->AddStyle("background: #def;")->Add(
        $navbar,
        uiContainer()->AddStyle("background: #efd;")->Add(
            uiHeader("Form Demonstration"),
            uiTabbable(array(
                "Default"=>uiForm($login),
                "Inline"=>uiForm($login,false,"inline"),
                "Horizontal"=>uiForm($login,false,"horizontal"),
                "Search"=>uiForm($search,false,"search"),
                "Misc"=>uiForm($misc,false,"horizontal")
                )
            )
        ),
        uiContainer()->Add(
            uiHeader(3,"The PHP code that generated this page:"),
            uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
        )
    );
