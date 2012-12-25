<?php
    require 'poof.php';

    // authenticate if we have a password
    $db=dbPoofSite();
    $config=$db->lookup();

    function login($data)
    {
        global $config;

        //echo uiPre(print_r($data,true));
        if ($data['email']==$config['email'] &&
            password_verify($data['pass'],$config['pass']))
        {
            $_SESSION['POOFSITE']['login']=time();
            echo uiAlert('success',"Logged in as site administrator");
        }
        else
        {
            echo uiAlert('error',"Invalid email or password");
        }
    }

    if (empty($_SESSION['POOFSITE']['login']) && 
        !empty($config['pass']) &&
        !empty($config['email']))
    {
        $fields=array(
            'email'=>array('type'=>"email",'desc'=>"Email",'required'=>true),
            'pass'=>array('type'=>"password",'desc'=>"Password",'required'=>true),
            'submit'=>array('type'=>"button",'desc'=>"Login")
        );
        echo uiPage("POOF Site Administration")->Add(
            uiWell()->Add(
                uiLegend("Login to access Site Administration"),
                uiForm($fields,false,'inline')->Post('login')
            )
        );
        return;
    }

    if (empty($_SESSION['POOFSITE']['login']))
    {
        echo uiPage("POOF Site Administration")->Add(
            uiWell()->Add(
                uiLegend("Site Administration"),
                uiAlert('info',"Please enter admin credentials"),
                uiEditRecord($db)
            )
        );
        return;
    }

    echo uiPage("POOF Site Administration")->Add(
        uiWell()->Add(
            uiLegend("Site Administration"),
            uiEditRecord($db)
        )
    );


