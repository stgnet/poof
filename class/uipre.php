<?php

class uipre extends uiElement
{
    public function __construct($text)
    {
        parent::__construct();
        $this->ui_tag="pre";
        $this->ui_text=$text;
    }
}
