<?php

class uiImage extends uiElement
{
	private $src;
	private $href;
	function __construct($src,$href)
	{
		parent::__construct();
		$this->src=$src;
		$this->href=$href;
	}

	function __toString()
	{
		return($this->Tag("a href=\"{$this->href}\"",
			$this->Tag("img src=\"{$this->src}\""))
		);
	}
}
