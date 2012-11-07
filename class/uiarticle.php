<?php

class uiArticle extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="article";
        $this->ui_class=$class;
    }
}
