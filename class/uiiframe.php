<?php

class uiIframe extends uiElement
{
    public function __construct($src)
    {
        parent::__construct();
        $this->ui_tag="iframe";
        $this->AddAttr('src',$src);
        $this->AddAttr('width','100%');
    }
    public function Height($number)
    {
        $this->SetAttr('height',$number);
    }
    public function Width($number)
    {
        $this->SetAttr('width',$number);
    }
}
