<?php

class uitooltip extends uiElement
{
    public function __construct($text)
    {
        parent::__construct();
        $this->ui_tag="a";
        //$this->ui_attr="rel=\"tooltip\" title=\"".htmlentities($text)."\"";
        $this->AddAttr('rel',"tooltip");
        $this->AddAttr('title',$text);
    }
    public function Top()
    {
        $this->AddAttr('data-placement','top');
        return($this);
    }
    public function Below()
    {
        $this->AddAttr('data-placement','bottom');
        return($this);
    }
    public function Left()
    {
        $this->AddAttr('data-placement','left');
        return($this);
    }
    public function Right()
    {
        $this->AddAttr('data-placement','right');
        return($this);
    }
    public function Animate()
    {
        $this->AddAttr('data-animation','');
        return($this);
    }
    public function Click()
    {
        $this->AddAttr('data-trigger','click');
        return($this);
    }
    public function Hover()
    {
        $this->AddAttr('data-trigger','hover');
        return($this);
    }
    public function Focus()
    {
        $this->AddAttr('data-trigger','focus');
        return($this);
    }
    public function Delay($ms)
    {
        $this->AddAttr('data-delay',$ms);
        return($this);
    }
    public function PreGenerate($page)
    {
        $page->ReadyScript('tooltip',"\$('a[rel=\"tooltip\"]').tooltip();");
    }
}
