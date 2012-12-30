<?php

class uinavbar extends uiElement
{
    public function __construct($brand=false,$menu=false)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="navbar";

        $inner=uiDiv("navbar-inner");

        if ($brand)
            $inner->Add(uiLink("#",$brand)->AddClass('brand'));

        if ($menu)
            $inner->Add(uiNavList($menu)->AddClass('pull-right'));

        $this->Add($inner);
        return($inner);
    }
}
