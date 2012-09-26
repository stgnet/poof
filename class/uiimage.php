<?php

class uiimage extends uiElement
{
    public function __construct($src)
    {
        parent::__construct();
        $this->ui_tag="img";
        //$this->ui_attr="src=\"$src\"";
        $this->AddAttr('src',$src);
    }
}
