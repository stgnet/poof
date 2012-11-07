<?php

class uiAside extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="aside";
        $this->ui_class=$class;
    }
}
