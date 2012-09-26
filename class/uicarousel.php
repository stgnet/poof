<?php

class uicarousel extends uiElement
{
    private $list;

    public function __construct($list=false)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="carousel slide";
        $this->list=$list;
    }
    public function PreGenerate($page)
    {
        $page->ReadyScript('carousel',"\$('.carousel').carousel();");
    }

    public function __toString()
    {
        $list='';
        $active=' active';
        if ($this->list) foreach ($this->list as $name => $src) {
            $list.=$this->Tag("div class=\"item$active\"",
                $this->Tag("img src=\"$src\"")
            );
            $active=false;
        }
        foreach ($this->GenerateContentArray() as $element) {
            $list.=$this->Tag("div class=\"item$active\"",$element);
            $active=false;
        }

        return($this->Tag($this->GenerateTag(),
            $this->Tag("div class=\"carousel-inner\"",$list)
        ));
    }
}
