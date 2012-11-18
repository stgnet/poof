<?php
    require 'poof.php';

	echo uiPage("POOF Demo")->Add(
        uiHeading("Table Edit Test"),
		uiContainer()->Add(
			uiEditable(dbCsv("demodata.csv"))
		),
		uiContainer()->Add(
			uiHeading(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable"),
			uiHeading(3,"Contents of demodata.csv:"),
			uiCodeMirror(file_get_contents("demodata.csv"))
		)
	);
