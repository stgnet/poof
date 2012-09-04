<?php
	require('poof.php');

	$navmenu=array('Home'=>"demo.php");
	foreach (arDir("demo")->Match("*.php") as $file)
		$navmenu[ucwords(basename($file,".php"))]=$file;

	$carousel=arDir("img")->Match("*/poof?.png");

	echo uiPage("POOF Demo")->AddStyle("background: #def;")->Add(
		uiContainer("navbar")->Add(
			uiContainer("navbar-inner")->AddStyle("background: #fed;")->Add(
				uiImage("img/poof.png","index.php")->AddClass("nav"),
				uiNavList($navmenu)->AddClass("pull-right")
			)
		),
		uiContainer()->Add(
			uiHero()->AddStyle("background: #efd;")->Add(
				uiCarousel($carousel)->AddClass("pull-right"),
				uiHeader("Hello, World!"),
				uiParagraph("This is a demostration of")->Add(
					uiTooltip("Programmatic Object-oriented Orthogonal Framework")->Add(
						"POOF"
					)
				),
				uiDropdown("Download Code")->AddClass("btn")->Add(
						uiLink("http://github.com/stgnet/poof","GitHub"),
						//uiLink("http://somewhere","Download")
						uiTooltip("Not currently available")->Add("Download")
				)
			)
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
