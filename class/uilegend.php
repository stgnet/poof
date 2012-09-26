<?php

class uilegend extends uiElement
{
    public function __construct($text=false)
    {
        parent::__construct();
        $this->ui_tag="legend";
        $this->ui_text=$text;
    }
}
