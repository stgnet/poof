<?php

// POOF_CONSTRUCT: uiInput_Cancel

class uiInput_Button extends uiInput_Base
{
    public function __construct($attr=false)
    {
        $valid=array('name');
        parent::__construct($attr,$valid);

        $this->ui_tag="button";
        $this->AddAttr('type',"submit");
        $this->AddClass("btn");

        if (!empty($attr['value']))
            $this->ui_html=str_replace(' ','&nbsp;',$attr['value']);
        else
            $this->ui_text="Submit";
    }
}
