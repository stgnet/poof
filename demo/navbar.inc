<?php
    require '../poof.php';
    poof_theme('cerulean');

	$navmenu=array('Home'=>"../demo.php");
	foreach (arDir(".")->Match("*.php")->Sort() as $file)
		$navmenu[ucwords(basename($file,".php"))]=$file;

	$navbar=uiGoogleAnalytics('UA-34982565-1','poof.stg.net')->Add(
		uiDiv("navbar")->Add(
			uiDiv("navbar-inner")->Add(
                uiLink("#","POOF")->AddClass("brand"),
				//uiImage("../img/poof.png","../demo.php")->AddClass("nav"),
				uiNavList($navmenu)->AddClass("pull-right")
			)
		)
	);

