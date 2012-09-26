<?php

class uiyoutube extends uiElement
{
    public function __construct($url,$width=640,$height=false)
    {
        if (!$height)
            $height=round($width/1.777);

        parent::__construct();
        $this->ui_tag="iframe";
        //$this->ui_attr="width=\"$width\" height=\"$height\" src=\"$url\" frameborder=\"0\" allowfullscreen";
        $this->AddAttr('width',$width);
        $this->AddAttr('height',$height);
        $this->AddAttr('src',$url);
        $this->AddAttr('frameborder',"0");
        $this->AddAttr('allowfullscreen',true);
    }
}
