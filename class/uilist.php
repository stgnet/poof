<?php

class uilist extends uiElement
{
    private $list;

    public function __construct($list=false)
    {
        parent::__construct();
        $this->ui_tag="ul";
        $this->list=$list;
    }

    public function __toString()
    {
        $list='';
        if ($this->list) foreach ($this->list as $name => $href)
            $list.=$this->Tag("li",
                $this->Tag("a href=\"$href\"",htmlentities($name))
            );

        foreach ($this->GenerateContentArray() as $element)
            $list.=$this->Tag("li",$element);

        return($list);
    }
}
