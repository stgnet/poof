<?php

class uiWell extends uiElement
{
    public function __construct($class=false)
    {
        parent::__construct();
        $this->ui_tag="div";
        $this->ui_class="well";
	if ($class) $this->AddClass($class);
    }
}
