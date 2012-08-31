<?php
	require('../poof.php');

	require('navbar.inc');

	echo uiPage("POOF Demo")->Add(
		$navbar,
		uiContainer()->Add(
			uiHero()->AddStyle("background: #def;")->Add(
				uiHeader("Hello, World!"),
				uiParagraph("This is a demostration of POOF"),
				uiButton("Download Code","http://github.com/stgnet/poof")->AddClass("btn-large")
			)
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiPre(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
