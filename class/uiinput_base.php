<?php

class uiInput_Base extends uiElement
{
	protected $desc;

	function __construct($attr=false,$valid)
	{
		parent::__construct();

		$this->desc=false;
		if (!empty($attr['desc']))
			$this->desc=$attr['desc'];


		if ($attr) foreach ($attr as $name => $value)
		{
			if ($name=='class')
				$this->AddClass($value);
			else
			if (in_array($name,$valid))
				$this->AddAttr($name,$value);

		}
	}
	function GetDescription()
	{
		return($this->desc);
	}
	function SetInlineDescription($desc)
	{
		// by default, set placeholder attribute
		// some types may override this behavior
		$this->AddAttr('placeholder',$desc);
	}
}