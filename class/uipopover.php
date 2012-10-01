<?php

class uipopover extends uiElement
{
    public function __construct($text)
    {
        parent::__construct();
        $text=htmlentities($text);
        $this->ui_tag="a";
        //$this->ui_attr="href=\"#\" rel=\"popover\" data-content=\"$text\"";
        $this->AddAttr('href',"#");
        $this->AddAttr('rel',"popover");
        $this->AddAttr('data-content',$text);
    }

    public function PreGenerate($page)
    {
        $page->ReadyScript('popover',"\$('a[rel=\"popover\"]').popover();");
    }
}
