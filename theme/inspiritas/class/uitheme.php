<?php

class uiTheme extends uiElement
{
    public function __construct($page)
    {
        // add bootstrap's components to the page
//        $page->StyleSheet('bootstrap','bootstrap.css');
        $page->StyleSheet('inspiritas','inspiritas.css');
        $page->PostScript('jquery','jquery.js');
        $page->PostScript('bootstrap','bootstrap.js');
    }
    public function __toString()
    {
        return(false);
    }
}
