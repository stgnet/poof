<?php

class uiImage extends uiElement
{
	private $src;
	private $href;
	function __construct($src,$href)
	{
		parent::__construct();
		//$this->ui_class="navbar";
		$this->src=$src;
		$this->href=$href;
	}

	function __toString()
	{
		$output=$this->Indent()."<a href=\"{$this->href}\"><img src=\"{$this->src}\" /></a>";
		return($output.$this->GenerateContent());
	}
}
