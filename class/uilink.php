<?php

class uilink extends uiElement
{
    public function __construct($href,$text=false)
    {
        parent::__construct();
        $this->ui_tag="a";
        $this->ui_text=$text;
        //$this->ui_attr="href=\"$href\"";
        $this->AddAttr('href',$href);
    }
}
