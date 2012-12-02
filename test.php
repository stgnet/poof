<?php
    require 'poof.php';
    poof_theme('united');

    $fields=array(
//        'key'=>array('type'=>"key"),
        'fname'=>array('type'=>"text",'desc'=>"First Name"),
        'lname'=>array('type'=>"text",'desc'=>"Last Name")
    );

    $db=dbCsv("testdb.csv")->SetFields($fields)->SetKey('key');

	echo uiPage("POOF Demo")->Add(
        uiHeading("Table Edit Test"),
		uiContainer()->Add(
			uiEditable($db,$fields)
		)
        /*
        ,
		uiContainer()->Add(
			uiHeading(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable"),
			uiHeading(3,"Contents of testdb.csv:"),
			uiCodeMirror(file_get_contents("testdb.csv"))
		)
        */
	);
