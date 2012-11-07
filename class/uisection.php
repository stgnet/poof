<?php

class uiSection extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="section";
        $this->ui_class=$class;
    }
}
