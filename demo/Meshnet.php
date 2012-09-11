<?php
	require('../poof.php');

	echo uiPage("Project Meshnet Clone as Demo")->Add(
		uiParagraph("This is a duplication of the Project Meshnet bootstrap based page,
				done with POOF as a demonstration")->AddClass("pagination-centered"),
		uiNavBar()->Add(
			uiNavList()->AddClass("pull-right")->Add(
				uiLink("https://github.com/cjdelisle/cjdns")->Add(
					uiIcon("icon-download"),
					" get cjdns"
				),
				uiDivider(),
				uiLink("http://hyperboria.net")->Add(
					uiIcon("icon-leaf"),
					" network"
				)
			),
			uiLink("https://projectmeshnet.org")->Add(
				uiImage("https://projectmeshnet.org/images/lead.png")
			),
			uiNavList()->AddClass("nav-collapse")->Add(
				uiLink("https://projectmeshnet.org")->Add(
					uiIcon("icon-home"),
					" home"
				),
				uiDivider(),
				uiLink("https://wiki.projectmeshnet.org/Getting_started")->Add(
					uiIcon("icon-plus-sign"),
					" get started"
				),
				uiDivider(),
				uiLink("https://forums.projectmeshnet.org")->Add(
					uiIcon("icon-comment"),
					" forum"
				),
				uiDivider(),
				uiLink("http://map.projectmeshnet.org")->Add(
					uiIcon("icon-map-marker"),
					" map"
				),
				uiDivider(),
				uiLink("https://wiki.projectmeshnet.org/MeshLocal")->Add(
					uiIcon("icon-user"),
					" local"
				),
				uiDivider(),
				uiLink("https://wiki.projectmeshnet.org")->Add(
					uiIcon("icon-info-sign"),
					" wiki"
				)
			)
		),
		uiContainer()->Add(
			uiHero()->Add(
				uiHeader(3,"Our object is..."),
				uiParagraph("")->Add(
					uiButton("more info","https://wiki.projectmeshnet.org/The_Plan")->AddClass("pull-right")
				)
			),
			uiDiv("row pagination-centered")->Add(
				uiDiv("span3")->Add(
					uiDiv("well")->Add(
						uiHeader("Get Started"),
						uiParagraph("")->Add(
							uiButton("guide","https://wiki.projectmeshnet.org/Getting_Started")->AddStyle("btn-large")
						)
					)
				),
				uiDiv("span3")->Add(
					uiDiv("well")->Add(
						uiHeader("Software"),
						uiParagraph("")->Add(
							uiButton("cjdns","https://github.com/cjdelisle/cjdns/")->AddStyle("btn-large")
						)
					)
				),
				uiDiv("span3")->Add(
					uiDiv("well")->Add(
						uiHeader("Hardware"),
						uiParagraph("")->Add(
							uiButton("meshlocals","https://wiki.projectmeshnet.org/MeshLocal")->AddStyle("btn-large")
						)
					)
				),
				uiDiv("span3")->Add(
					uiDiv("well")->Add(
						uiHeader("Discuss"),
						uiParagraph("")->Add(
							uiButton("dev chat","http://chat.efnet.org:9090/?channels=%23cjdns&Login=Login")->AddStyle("btn-large")
						)
					)
				)
			)

		),
		uiContainer()->Add(
			uiHeader(3,"The PHP code that generated this page:"),
			uiCodeMirror(file_get_contents($_SERVER['SCRIPT_FILENAME']))->AddClass("pre-scrollable")
		)
	);
