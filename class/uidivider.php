<?php

class uidivider extends uiElement
{
    // this is meant to be added to a List object
    public function __construct()
    {
        parent::__construct();
        $this->ui_tag="li";
        $this->ui_class="divider-vertical";
    }
}
