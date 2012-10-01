<?php

class uiicon extends uiElement
{
    public function __construct($name)
    {
        parent::__construct();
        $this->ui_tag="i";
        $this->ui_class=$name;
    }
}
