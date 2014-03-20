POOF
====

POOF is a Programmatic, Object Oriented, and Orthogonal Framework for PHP, using a
non-conventional approach to Web development.

The purpose of this framework is to hide nearly all aspects of HTML, AJAX, and CSS inside high
level generic objects, providing for rapid development and easy modifications without
the conventional multi-file MVC or template approach.

If you want to create just a few quickly developed web pages with modern whizz-bang features, and
would prefer easy modifications in a single php rather than templates and MVC, then POOF is just
right.

Design Concepts
---------------
* High level code is extremely easy to read, compact, and correlates directly to result
* Object chains similar to [EDEN](http://eden.openovate.com/) allow flexibility and extensibility
* Many complex operations are reduced to a line or few of code
* Each class *foobar* has convenience function `foobar()` that returns `new foobar()`
* Use best practices, *except* where they a) detract from readability, b) slow development
* Instrumentation and diagnostics built in, so finding that bug or slow bit of code is a snap

Getting Started
---------------
* View the demo at [poof.stg.net](http://poof.stg.net) for coding examples
* Clone POOF to your project directory

```bash
mkdir myproject ; cd myproject
git clone https://github.com/stgnet/poof
cp poof/demo.php index.php
etc...
```

Hello World
-----------
A minimalistic code example:

```php
require('poof/poof.php');

echo uiPage("Page Title")->Add(
    uiContainer()->Add(
        uiHero()->Add(
            uiHeading("Hello, World!"),
            uiParagraph("It works!")
        )
    )
);
```

Bug Reports, Feature Requests, Code Contributions
-------------------------------------------------
* Working Demonstration at [poof.stg.net](http://poof.stg.net)
* Report issues on [GitHub](https://github.com/stgnet/poof/issues)
* [Pivotal Tracker](https://www.pivotaltracker.com/projects/641527)
* [Source on github](https://github.com/stgnet/poof)
* Documentation (still to come)
* [Travis-CI](http://travis-ci.org/stgnet/poof) ![](https://secure.travis-ci.org/stgnet/poof.png)
* [GitTip](https://www.gittip.com/stgnet/)
* Email [scott@griepentrog.com](mailto:scott@griepentrog.com)

Class Types in POOF
-------------------
* uiXXX - user interface components for programmatic html5 generation
* arXXX - array handling and manipulation
* dbXXX - database (SQL, CSV, etc) interface
* mlXXX - markup langage (nested tree structure)
* siXXX - singleton class (not true singleton, just convenience wrapper) 
* pfXXX - poof base classes (and misc)

LICENSE 
------- 
POOF is licensed under [Apache License V2](http://www.apache.org/licenses/LICENSE-2.0.html)

Requirements
------------
* POOF requires PHP 5.3 or later

Included Projects
-----------------
POOF includes and uses code from these fine open source projects:

* [jQuery](http://jquery.com) for AJAX
* [Twitter Bootstrap](http://twitter.github.com/bootstrap) for CSS/HTML5
* [Code Mirror](http://codemirror.net) for code viewing and editing
* [XMPPHP](http://code.google.com/p/xmpphp/) for Jabber (Gtalk)
* [Suin's PHP FTP Client](http://github.com/suin/php-ftp-client)

Future Improvements
-------------------
Planning on including features from:

* [jQuery UI Bootstrap](http://addyosmani.github.com/jquery-ui-bootstrap/)
* [Kickstrap](http://ajkochanowicz.github.com/Kickstrap)
* [Colorpicker and Datepicker](http://www.eyecon.ro/colorpicker-and-datepicker-for-twitter-bootstrap.htm)
* [Balanced Payments](https://www.balancedpayments.com/)
* [PHP Colors](http://mexitek.github.com/phpColors/)

