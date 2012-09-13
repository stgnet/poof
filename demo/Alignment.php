<?php
	require('../poof.php');
	require('navbar.inc');

	$red="#f88";
	$green="#8f8";
	$blue="#aaf";
	$grey="#aaa";

	echo uiPage("POOF Demo")->Background("#def")->Add(
		$navbar,
		uiContainer()->Background("#efd")->Add(
			uiHeader("Alignment Demonstration")
		),

		uiParagraph(""),

		// order is important: center must be last for all 3 to appear on same line
		uiParagraph('Uncontained paragraph left')->Background($red)->Left(),
		uiParagraph('Uncontained paragraph right')->Background($green)->Right(),
		uiParagraph('Uncontained paragraph center')->Background($blue)->Center(),

		uiParagraph(""),

		// same thing, but inside a container
		uiContainer()->Border()->Add(
			uiParagraph("Paragraph in same container left")->Background($red)->Left(),
			uiParagraph("Paragraph in same container right")->Background($green)->Right(),
			uiParagraph("Paragraph in same container center")->Background($blue)->Center()
		),

		uiParagraph(""),

		// what happens if you put each in it's own container
		uiContainer()->Border()->Add(
			uiParagraph("Paragraph in separate container left")->Background($red)->Left()
		),
		uiContainer()->Border()->Add(
			uiParagraph("Paragraph in separate container right")->Background($green)->Right()
		),
		uiContainer()->Border()->Add(
			uiParagraph("Paragraph in separate container center")->Background($blue)->Center()
		),

		uiHeader(3,"SPANS WITHOUT CONTAINER, FLUID OFF:")->Center(),
		AllTheSpans("",false),

		uiHeader(3,"SPANS INSIDE CONTAINER, FLUID OFF:")->Center(),
		uiContainer()->Add(
			AllTheSpans(' in container',false)
		),

		uiHeader(3,"SPANS WITHOUT CONTAINER, FLUID ON:")->Center(),
		AllTheSpans(" fluid",true),

		uiHeader(3,"SPANS INSIDE CONTAINER, FLUID ON:")->Center(),
		uiContainer()->Add(
			AllTheSpans(' in container and fluid',true)
		),

		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);

	function AllTheSpans($msg='',$fluid=true)
	{
		global $red,$blue,$green;
		if ($fluid)
			$fluid="row-fluid";
		else
			$fluid="row";

		return
		uiDiv()->Add(
			uiDiv($fluid)->Add(
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				),
				uiSpan(1)->Background($red)->Add(
					uiParagraph("Span 1$msg")
				)
			),
			uiDiv($fluid)->Add(
				uiSpan(2,1)->Background($green)->Add(
					uiParagraph("Span 2 offset 1$msg")
				),
				uiSpan(2)->Background($green)->Add(
					uiParagraph("Span 2$msg")
				),
				uiSpan(2)->Background($green)->Add(
					uiParagraph("Span 2$msg")
				),
				uiSpan(2)->Background($green)->Add(
					uiParagraph("Span 2$msg")
				),
				uiSpan(2)->Background($green)->Add(
					uiParagraph("Span 2$msg")
				)
			),
			uiDiv($fluid)->Add(
				uiSpan(3)->Background($blue)->Add(
					uiParagraph("Span 3$msg")
				),
				uiSpan(3)->Background($blue)->Add(
					uiParagraph("Span 3$msg")
				),
				uiSpan(3)->Background($blue)->Add(
					uiParagraph("Span 3$msg")
				),
				uiSpan(3)->Background($blue)->Add(
					uiParagraph("Span 3$msg")
				)
			),
			uiDiv($fluid)->Add(
				uiSpan(4)->Background($red)->Add(
					uiParagraph("Span 4$msg")
				),
				uiSpan(4)->Background($red)->Add(
					uiParagraph("Span 4$msg")
				),
				uiSpan(4)->Background($red)->Add(
					uiParagraph("Span 4$msg")
				)
			),
			uiDiv($fluid)->Add(
				uiSpan(5,1)->Background($green)->Add(
					uiParagraph("Span 5 offset 1$msg")
				),
				uiSpan(5)->Background($green)->Add(
					uiParagraph("Span 5$msg")
				)
			),
			uiDiv($fluid)->Add(
				uiSpan(6)->Background($blue)->Add(
					uiParagraph("Span 6$msg")
				),
				uiSpan(6)->Background($blue)->Add(
					uiParagraph("Span 6$msg")
				)
			)
		);
	}
