<?php

class uiscript extends uiElement
{
    public function __construct($code)
    {
        parent::__construct();
        $this->ui_tag="script";
        //$this->ui_attr="type=\"text/javascript\"";
        $this->AddAttr('type',"text/javascript");
        $this->ui_html=$code;
    }
}
