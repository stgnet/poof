<?php

class uiInput_Checkbox extends uiInput_Base
{
    public function __construct($attr=false)
    {
        $valid=array('type','name');
        parent::__construct($attr,$valid);

        $this->ui_tag="input";
    }
    public function SetInlineDescription($desc)
    {
        $this->Add(htmlentities($desc));
    }
}
