<?php
	$navmenu=array('Home'=>"../demo.php");
	foreach (arDir(".")->Match("*.php")->Sort() as $file)
		$navmenu[ucwords(basename($file,".php"))]=$file;

	$navbar=uiGoogleAnalytics('UA-34982565-1','poof.stg.net')->Add(
		uiContainer("navbar")->Add(
			uiContainer("navbar-inner")->AddStyle("background: #fed;")->Add(
				uiImage("../img/poof.png","../demo.php")->AddClass("nav"),
				uiNavList($navmenu)->AddClass("pull-right")
			)
		)
	);

