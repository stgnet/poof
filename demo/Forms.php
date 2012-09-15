<?php
	require('../poof.php');
	require('navbar.inc');

	$fields=array(
		'username'=>array('type'=>"text",'desc'=>"Email"),
		'password'=>array('type'=>"password",'desc'=>"Password"),
		'remember'=>array('type'=>"checkbox",'desc'=>"Remember Me")
	);

/*
	$form1=uiDiv()->Add(
		uiForm()->Add(
			uiLegend("Legend goes here"),
			uiLabel("Username"),
			uiInput('username','text',array('placeholder'=>"user@gmail.com"))
		)
	);
*/

	echo uiPage("POOF Demo")->AddStyle("background: #def;")->Add(
		$navbar,
		uiContainer()->AddStyle("background: #efd;")->Add(
			uiHeader("Demonstration of Forms"),
			uiTabbable(array(
				"Default"=>uiForm($fields),
				"Inline"=>uiForm($fields,false,"inline"),
				"Horizontal"=>uiForm($fields,false,"horizontal")
				)
			)
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
