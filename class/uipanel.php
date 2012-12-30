<?php

class uiPanel extends uiElement
{
    private $inner;
    public function __construct($name)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->AddStyle("margin: 2%;");

        $this->inner=uiWell();
        parent::Add($this->inner);

        if ($name)
            $this->inner->Add(uiLegend($name));
    }
    // make sure that adds are put in inner
    public function Add()
    {
        $args=func_get_args();
        call_user_func_array(array($this->inner,'Add'),$args);
        return $this;
    }
}
