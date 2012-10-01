<?php

class uigittip extends uiElement
{
    public function __construct($username)
    {
	$url="https://www.gittip.com/$username/widget.html";
	$width=48;
	$height=20;

        parent::__construct();
        $this->ui_tag="iframe";
	$this->AddStyle("border: 0; margin: 0; padding 0;");
        $this->AddAttr('width',$width."px");
        $this->AddAttr('height',$height."px");
        $this->AddAttr('src',$url);
    }
}
