<?php

class uiAlert extends uiElement
{
    public function __construct($class=false,$text=false)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="alert";
        $this->ui_text=$text;
        if ($class)
        {
            if (substr($class,0,6)!="alert-")
                $class="alert-".$class;
            $this->AddClass($class);
        }
    }
}
