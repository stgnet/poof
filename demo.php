<?php
	require('poof.php');

	$meta=array(
		'title'=>"POOF Framework Demonstration",
		'keywords'=>"POOF, PHP, Framework, Object-oriented, Orthogonal",
		'description'=>"Diagnostics tool for framework code",
		'viewport'=>"width=device-width, initial-scale=1.0",
		'author'=>"Scott Griepentrog scott@griepentrog.com",
	);

	$navmenu=array(
		'One'=>"one.php",
		'Two'=>"two.php",
		'Three'=>"three.php",
	);

	echo uiPage($meta)->Add(
		uiContainer("navbar")->Add(
			uiContainer()->Add(
				uiImage("img/poof.png","index.php")->AddClass("nav"),
				uiNavList($navmenu)->AddClass("pull-right")
			)
		),
		uiContainer()->Add(
			uiHero()->Add(
				uiHeader("Hello, World!"),
				uiParagraph("This is a demostration of POOF"),
				uiButton("Download Code","http://github.com/stgnet/poof")->AddClass("btn-large")
			)
		),
		uiContainer()->Add(
			uiHeader(3,"This PHP code generated this page:"),
			uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))
		)
	);
