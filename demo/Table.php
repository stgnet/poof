<?php
    require 'navbar.inc';

	echo uiPage("POOF Demo")->Add(
		$navbar,
		uiContainer()->Add(
			uiTable(dbCsv("demodata.csv"))
		),
		uiContainer()->Add(
			uiHeading(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable"),
			uiHeading(3,"Contents of demodata.csv:"),
			uiCodeMirror(file_get_contents("demodata.csv"))
		)
	);
