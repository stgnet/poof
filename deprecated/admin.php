<?php
    require 'poof.php';

    // authenticate if we have a password
    $db=dbPoofSite();
    $config=$db->lookup();

    function login_post($data)
    {
        global $config;
        global $POOF_HOST,$POOF_URL;

        //echo uiPre(print_r($data,true));
        if ($data['email']==$config['email'] &&
            password_verify($data['pass'],$config['pass']))
        {
            $_SESSION['POOFSITE']['login']=time();
            // redirect to myself to force correct url
            header("Location: http://$POOF_HOST$POOF_URL/admin.php");
            echo uiPage('Logged In')->Add(
                uiAlert('success',"Logged in as site administrator")
            )->ReloadAfter(3);
            return(true);
        }
        else
        {
            echo uiAlert('error',"Invalid email or password");
        }
    }

    $login_form_fields=array(
        'email'=>array('type'=>"email",'desc'=>"Email",'required'=>true),
        'pass'=>array('type'=>"password",'desc'=>"Password",'required'=>true),
        'submit'=>array('type'=>"button",'desc'=>"Login")
    );

    function login_form()
    {
        global $login_form_fields;

        return(
            uiPanel("Please log into administrator account")->Add(
                uiForm($login_form_fields,false,'inline')->Post('login_post')
            )
        );
    }


    if (empty($_SESSION['POOFSITE']['login']) && 
        !empty($config['pass']) &&
        !empty($config['email']))
    {
        echo uiPage("POOF Site Administration")->Add(login_form());
        return;
    }

    if (empty($_SESSION['POOFSITE']['login']))
    {
        echo uiPage("POOF Site Administration")->Add(
            uiPanel("Site Administrator Credentials")->Add(
                uiAlert('info',"Please set the admin credentials to secure future access to the adminstration tools."),
                uiEditRecord($db)
            )
        );
        return;
    }

    $navmenu=array(
        'Config'=>"admin.php",
        'Errors'=>"errors.php",
        'Discern'=>"discern.php",
        'Console'=>"ssh.php"
    );

    echo uiPage("POOF Site Administration")->Add(
        uiNavBar("POOF Admin",$navmenu),
        uiPanel("Site Administration")->Add(
            uiEditRecord($db)->PostUrl($_SERVER['SCRIPT_NAME'])
        )
    );


