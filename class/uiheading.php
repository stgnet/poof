<?php

class uiHeading extends uiElement
{
    public function __construct($level,$text=false)
    {
        parent::__construct();
        if (!$text) {
            $text=$level;
            $level=1;
        }
        $this->ui_tag="h$level";
        $this->ui_text=$text;
    }
}
