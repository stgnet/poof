<?php
	require('poof.php');

	$meta=array(
		'title'=>"POOF Framework Demonstration",
		'keywords'=>"POOF, PHP, Framework, Object-oriented, Orthogonal",
		'description'=>"Diagnostics tool for framework code",
		'viewport'=>"width=device-width, initial-scale=1.0",
		'author'=>"Scott Griepentrog scott@griepentrog.com",
	);

	$logo=new uiImage("http://placehold.it/143x45",'index.php');

	$navmenu=array(
		'One'=>"one.php",
		'Two'=>"two.php",
		'Three'=>"three.php",
	);

	echo uiPage($meta)->AddClass("wrap")->Add(
		uiDiv("navbar navbar-fixed-top")->Add(
uiDiv("navbar-inner")->Add(
uiDiv("container")->Add(
			uiImage("img/poof.png","index.php")->AddClass("pull-left"),
			uiNavBar($navmenu)->AddClass("pull-right")
))
		)
,
		uiContainer()->Add(
		uiHero()->Add(
			uiHeader("Hello, World!"),
			uiParagraph("This is a demostration of POOF"),
			uiButton("Code","http://github.com/stgnet/poof")
		)
		)
	);
