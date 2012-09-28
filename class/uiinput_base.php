<?php

class uiInput_Base extends uiElement
{
    protected $desc;

    public function __construct($attr=false,$valid)
    {
        parent::__construct();

        $this->desc=false;
        if (!empty($attr['desc']))
            $this->desc=$attr['desc'];

        if ($attr) foreach ($attr as $name => $value) {
            if ($name=='class')
                $this->AddClass($value);
            else
            if (in_array($name,$valid) || $name=='name')
                $this->AddAttr($name,$value);

        }
    }
    public function GetDescription()
    {
        return($this->desc);
    }
    public function SetInlineDescription($desc)
    {
        // by default, set placeholder attribute
        // some types may override this behavior
        $this->AddAttr('placeholder',$desc);
    }
}
