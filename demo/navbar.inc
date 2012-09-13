<?php
	$navmenu=array('Home'=>"../demo.php");
	foreach (arDir(".")->Match("*.php")->Sort() as $file)
		$navmenu[ucwords(basename($file,".php"))]=$file;

	$navbar=uiContainer("navbar")->Add(
		uiContainer("navbar-inner")->AddStyle("background: #fed;")->Add(
			uiImage("../img/poof.png","../demo.php")->AddClass("nav"),
			uiNavList($navmenu)->AddClass("pull-right")
		)
	);

