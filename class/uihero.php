<?php

class uihero extends uiElement
{
    public function __construct()
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="hero-unit";
    }
}
