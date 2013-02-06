<?php

require_once('glyphicons.php');

class uiicon extends uiElement
{
    private $css;

    public function __construct($name)
    {
        parent::__construct();
        $this->ui_tag="i";
        if (substr($name,0,5)!="icon-")
            $name="icon-".$name;
        $this->ui_class=$name;

        // check the icon name and load fontawesome if unknown
        $justname=explode(' ',$name);
        if (!in_array($justname[0],$GLOBALS['glyphicons']))
            $this->css['fontawesome']="fontawesome/css/font-awesome.min.css";
    }

    public function PreGenerate($page)
    {
        foreach (safearray($this->css) as $name => $file)
            $page->Stylesheet($name,$file);
    }
}
