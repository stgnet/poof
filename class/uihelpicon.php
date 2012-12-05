<?php

class uiHelpIcon extends uiElement
{
    public function __construct($text)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->Add(uiTooltip($text)->Below()->Add(uiIcon('question-sign')));
    }
}
