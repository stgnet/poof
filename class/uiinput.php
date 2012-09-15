<?php

class uiInput extends uiElement
{
	protected $name;

	function __construct($name,$type='text',$attr=false)
	{
		parent::__construct();
		$this->ui_name=$name; // override the default name
		$this->ui_tag="input";
		$this->ui_attr="name=\"{$this->ui_name}\" type=\"$type\"";
		$this->name=$name;

		if ($attr) foreach ($attr as $left => $right)
			$this->ui_attr.=" $left=\"$right\"";
	}
}
