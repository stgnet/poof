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

	echo uiPage($meta)->Add(
		uiContainer("navbar")->Add(
			uiContainer()->Add(
				uiImage("img/poof.png","index.php")->AddClass("nav pull-left"),
				uiNavList($navmenu)->AddClass("pull-right")
			)
		),
		uiContainer()->Add(
			uiHero()->Add(
				uiHeader("Hello, World!"),
				uiParagraph("This is a demostration of POOF"),
				uiButton("Code","http://github.com/stgnet/poof")
			)
		)
	);
