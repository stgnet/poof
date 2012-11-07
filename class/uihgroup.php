<?php

class uiHGroup extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="hgroup";
        $this->ui_class=$class;
    }
}
