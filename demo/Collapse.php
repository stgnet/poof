<?php
	require('../poof.php');
	require('navbar.inc');

	echo uiPage("POOF Demo")->AddStyle("background: #def;")->Add(
		$navbar,
		uiContainer()->AddStyle("background: #efd;")->Add(
			uiHeader("Demonstration of Collapsable sections"),
			uiCollapse(array(
				"One"=>uiParagraph("This is the first section"),
				"Two"=>uiParagraph("This is the second section"),
				"Three"=>uiParagraph("This is the third section")
				)
			)
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
