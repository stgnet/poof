<?php

class uiHeader extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="header";
        $this->ui_class=$class;
    }
}
