<?php

class uiBadge extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="span";
        $this->ui_class="badge";
        if ($class)
        {
            if (substr($class,0,6)!="badge-")
                $class="badge-".$class;
            $this->AddClass($class);
        }
    }
}
