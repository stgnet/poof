<?php
	$navmenu=array('Home'=>"../demo.php");
	foreach (arDir(".")->Match("*.php") as $file)
		$navmenu[ucwords(basename($file,".php"))]=$file;

	$navbar=uiContainer("navbar")->AddStyle("background: #fed;")->Add(
		uiContainer()->Add(
			uiImage("../img/poof.png","../demo.php")->AddClass("nav"),
			uiNavList($navmenu)->AddClass("pull-right")
		)
	);

