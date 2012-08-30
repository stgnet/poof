<?php

class uiHeader extends uiElement
{
	private $level;
	private $text;

	function __construct($level,$text=false)
	{
		parent::__construct();
		if (!empty($text))
		{
			$this->level=$level;
			$this->text=$text;
		}
		else
		{
			$this->level=1;
			$this->text=$level;
		}
	}

	function __toString()
	{
		return("<h{$this->level}>".htmlentities($this->text)."</h{$this->level}>". $this->GenerateContent());
	}
}
