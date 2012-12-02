<?php
/**
 * Button with an Icon
 * @package poof
 */

/**
 * Button with an Icon
*/
class uiIconButton extends uiElement
{
    /**
     * construct a button that contains an icon
     * @param string $icon name of icon-? to label button
     * @param string $name optional alternate name for button
     */
    public function __construct($icon,$name=false)
    {
        parent::__construct();
        $this->ui_tag="button";
        $this->ui_class="btn btn-small";

        if (!$name)
            $name=$icon;

        $this->AddAttr('name',$name);

        $this->Add(uiIcon($icon));
    }
}
