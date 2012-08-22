<?php

class uiHeader extends uiElement
{
	private $text;

	function uiHeader($text)
	{
		$this->UniqName();
		$this->text=$text;
	}

	function Generate()
	{
		echo "<h1>".htmlentities($this->text)."</h1>";

		$this->GenerateContent();
	}
}
