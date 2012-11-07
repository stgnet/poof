<?php

class uiNav extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="nav";
        $this->ui_class=$class;
    }
}
