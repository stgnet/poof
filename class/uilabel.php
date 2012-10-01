<?php

class uilabel extends uiElement
{
    public function __construct($text=false)
    {
        parent::__construct();
        $this->ui_tag="label";
        $this->ui_text=$text;
    }
}
