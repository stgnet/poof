<?php

class uiInput_Textarea extends uiInput_Base
{
    public function __construct($attr=false)
    {
        $valid=array('type','name','rows','cols');
        parent::__construct($attr,$valid);

        $this->ui_tag="textarea";
    }
}
