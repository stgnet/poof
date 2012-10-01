<?php

class uiparagraph extends uiElement
{
    private $text;

    public function __construct($text=false)
    {
        parent::__construct();
        $this->ui_tag="p";
        $this->ui_text=$text;
    }
}
