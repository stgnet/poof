<?php

// These are alternate names for this class:
// POOF_CONSTRUCT: uiInput_Password
// POOF_CONSTRUCT: uiInput_Checkbox

class uiInput_Text extends uiElement
{
	protected $attr;

	function __construct($attr=false)
	{
		parent::__construct();
		if (!$attr)
			$attr=array('type'=>"text",'name'=>$this->ui_name);

		$this->ui_tag="input";
		$this->attr=$attr;

		foreach ($attr as $name => $value)
		{
			if (in_array($name,array('type','name','placeholder')))
				$this->ui_attr.=" $name=\"$value\"";
		}
	}
	function GetDescription()
	{
		if (!empty($this->attr['desc']))
			return($this->attr['desc']);
		return(false);
	}
}
