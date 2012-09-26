<?php

class uiInput_Radio extends uiInput_Base
{
    public function __construct($attr=false)
    {
        $valid=array('type','name');
        parent::__construct($attr,$valid);

        $this->ui_tag=false;
        if (empty($attr['options'])) Fatal("Radio requires options list");
        $index=1;
        foreach ($attr['options'] as $value => $desc) {
            $id=$this->ui_name.'_'.$index++;
            $this->ui_html.=$this->Tag("label class=\"radio\"",
                $this->Tag("input type=\"radio\" name=\"{$this->ui_name}\" id=\"$id\" value=\"$value\"").htmlentities($desc));
        }
    }
    public function SetInlineDescription($desc)
    {
        $this->Add(htmlentities($desc));
    }
}
