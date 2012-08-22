<?php

class uiPage extends uiElement
{
	function __construct()
	{
		$this->UniqName('page');
	}

	function GenerateHead()
	{
		echo "<title>Change This</title>";
	}
	function GenerateMeta()
	{
	}

	// other UI elements define their own Generate, but must also call GenerateContent
	function Generate()
	{
		if ($_SERVER['REQUEST_METHOD']=='POST')
		{
			if ($this->HandlePost())
				return;
		}

		echo "<html>";

		echo "<head>";
		$this->GenerateHead();
		$this->GenerateMeta();
		echo "</head>";

		echo "<body><div id=\"{$this->ui_name}\">\n";
		$this->GenerateContent();

		echo "</div></body></html>\n";
	}

}
