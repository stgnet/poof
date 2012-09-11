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
* Object chains similar to EDEN allow flexibility and extensibility
* Many complex operations are reduced to a line or few of code

Getting Started
---------------

View [demo.php](https://github.com/stgnet/poof/blob/master/demo.php) for an example.  To create your own
project, clone poof into your project directory, copy poof/demo.php to index.php and modify to build
your own website.

Hello World
-----------
A code example:

	require('/poof/poof.php');

	echo uiPage("Page Title")->Add(
		uiContainer()->Add(
			uiHero()->Add(
				uiHeader("Hello, World!"),
				uiParagraph("It works!")
			)
		)
	);


Components in POOF
------------------

* uiXXX - user interface components for modern style web page creation on the fly from program code
* arXXX - array handling and manipulation
* dbXXX - database (SQL, CSV, etc) interface

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


