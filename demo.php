<?php
	require('poof.php');

	$navmenu=array('Home'=>"demo.php");
	foreach (arDir("demo")->Match("*.php") as $file)
		$navmenu[ucwords(basename($file,".php"))]="demo/$file";

	echo uiPage("POOF Demo")->Add(
		uiContainer("navbar")->Add(
			uiContainer("navbar-inner")->AddStyle("background: #fed;")->Add(
				uiImage("img/poof.png","index.php")->AddClass("nav"),
				uiNavList($navmenu)->AddClass("pull-right")
			)
		),
		uiContainer()->Add(
			uiHero()->AddStyle("background: #def;")->Add(
				uiHeader("Hello, World!"),
				uiParagraph("This is a demostration of POOF -
					Programmatic Object-oriented Orthogonal Framework"),
				uiButton("Download Code","http://github.com/stgnet/poof")->AddClass("btn-large")
			)
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
