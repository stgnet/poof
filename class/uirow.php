<?php

class uirow extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="row";
        if ($class)
            $this->AddClass($class);
    }
}
