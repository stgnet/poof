<?php
	require('poof.php');

	global $POOF_UI_DEBUG;

	if (!empty($GLOBALS['debug']))
		$POOF_UI_DEBUG=true;

	$meta=array(
		'title'=>"POOF Framework Demonstration",
		'keywords'=>"POOF, PHP, Framework, Object-oriented, Orthogonal",
		'description'=>"Diagnostics tool for framework code",
		'viewport'=>"width=device-width, initial-scale=1.0",
		'author'=>"Scott Griepentrog scott@griepentrog.com",
	);

	$logo=new uiImage("http://placehold.it/143x45",$_SERVER['SCRIPT_NAME']);

	$navmenu=array(
		'One'=>"one.php",
		'Two'=>"two.php",
		'Three'=>"three.php",
	);

	$navbar=new uiNavBar($navmenu);
	$navbar->AddClass("pull-right");

	$navbar_container=new uiContainer();

	$navbar_container->Add($logo);
	$navbar_container->Add($navbar);


	$page=new uiPage($meta);
	$page->Add($navbar_container);

	$page->Add(new uiHeader("POOF Diagnostic Tool"));
	//$page->Add(new uiSession());
	$page->Add(new uiDebug('POOF_DIR'));
	$page->Add(new uiDebug('POOF_URL'));
	$page->Add(new uiDebug('_SESSION'));
	$page->Add(new uiDebug('_SERVER'));
	$page->Generate();
