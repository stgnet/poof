<?php

class uiicon extends uiElement
{
    public function __construct($name)
    {
        parent::__construct();
        $this->ui_tag="i";
        if (substr($name,0,5)!="icon-")
            $name="icon-".$name;
        $this->ui_class=$name;
    }
}
