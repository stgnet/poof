POOF
====

POOF is a Programmatic, Object Oriented, and Orthogonal Framework for PHP, using a
non-conventional approach to Web development.

The purpose of this framework is to hide all aspects of HTML, AJAX, and CSS inside high
level generic objects, providing for rapid development and easy modifications without
the conventional multi-file MVC or template approach.

Design Concepts
---------------
* High level code is extremely easy to read, compact, and correlates directly to result
* Object chains similar to [EDEN](http://eden.openovate.com/) allow flexibility and extensibility
* Many complex operations are reduced to a line or few of code
* Each class *foobar* has convenience function `foobar()` that returns `new foobar()`

Getting Started
---------------

* View the demo at [poof.stg.net](http://poof.stg.net) for coding examples
* Clone POOF to your project directory

    # mkdir myproject ; cd myproject
    # git clone https://github.com/stgnet/poof
    # cp poof/demo.php index.php
    etc...


Hello World
-----------
A minimalistic code example:

	require('poof/poof.php');

	echo uiPage("Page Title")->Add(
		uiContainer()->Add(
			uiHero()->Add(
				uiHeader("Hello, World!"),
				uiParagraph("It works!")
			)
		)
	);


Bug Reports, Feature Requests, Code Contributions
-------------------------------------------------
* Report issues on [GitHub](https://github.com/stgnet/poof/issues)
* [Pivotal Tracker](https://www.pivotaltracker.com/projects/641527)
* [Source on github](https://github.com/stgnet/poof)
* Documentation (still to come)
* [Travis-CI](http://travis-ci.org/stgnet/poof) ![](https://secure.travis-ci.org/stgnet/poof.png)
* [GitTip](https://www.gittip.com/stgnet/)
* Email [scott@griepentrog.com](mailto:scott@griepentrog.com)

Class Types in POOF
-------------------
* uiXXX - user interface components for modern style web page creation on the fly from program code
* arXXX - array handling and manipulation
* dbXXX - database (SQL, CSV, etc) interface
* mlXXX - markup langage (nested tree structure)
* siXXX - singleton class (not true singleton, just convenience wrapper) 

LICENSE 
------- 
POOF is licensed under [Apache License V2](http://www.apache.org/license/LICENSE-2.0)

Included Projects
-----------------
POOF includes and uses code from these fine open source projects:

* [jQuery](http://jquery.com) for AJAX
* [Twitter Bootstrap](http://twitter.github.com/bootstrap) for CSS/HTML5
* [Code Mirror](http://codemirror.net) for code viewing and editing

Future Improvements
-------------------
Planning on including features from:

* [jQuery UI Bootstrap](http://addyosmani.github.com/jquery-ui-bootstrap/)
* [Kickstrap](http://ajkochanowicz.github.com/Kickstrap)
* [Colorpicker and Datepicker](http://www.eyecon.ro/colorpicker-and-datepicker-for-twitter-bootstrap.htm)
* [Balanced Payments](https://www.balancedpayments.com/)
* [PHP Colors](http://mexitek.github.com/phpColors/)

