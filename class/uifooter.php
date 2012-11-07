<?php

class uiFooter extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="footer";
        $this->ui_class=$class;
    }
}
