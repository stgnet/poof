<?php
	require('../poof.php');
	require('navbar.inc');

	echo uiPage("POOF Demo")->AddStyle("background: #def;")->Add(
		$navbar,
		uiContainer()->AddStyle("background: #efd;")->Add(
			uiHeader("Alignment Demonstration")
		),
		uiParagraph('Uncontained paragraph left')->AddStyle("background: #f88;")->Left(),
		uiParagraph('Uncontained paragraph center')->AddStyle("background: #8f8;")->Center(),
		uiParagraph('Uncontained paragraph right')->AddStyle("background: #88f;")->Right(),
		uiContainer()->Add(
			uiParagraph("Paragraph in container left")->AddStyle("background: #f88;")->Left()
		),
		uiContainer()->Add(
			uiParagraph("Paragraph in container center")->AddStyle("background: #8f8;")->Center()
		),
		uiContainer()->Add(
			uiParagraph("Paragraph in container right")->AddStyle("background: #88f;")->Right()
		),
		uiContainer()->Add(
			uiParagraph("Paragraph in same container left")->AddStyle("background: #f88;")->Left(),
			uiParagraph("Paragraph in same container center")->AddStyle("background: #8f8;")->Center(),
			uiParagraph("Paragraph in same container right")->AddStyle("background: #88f;")->Right()
		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
