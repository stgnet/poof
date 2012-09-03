<?php
	require('../poof.php');
	require('navbar.inc');

	echo uiPage("POOF Demo")->AddStyle("background: #efd;")->Add(
		$navbar,
		uiContainer()->AddStyle("background: #def;")->Add(
			uiTable(dbCsv("demodata.csv"))
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
