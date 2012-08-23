<?php

class uiPage extends uiElement
{
	private $ui_meta;

	function __construct($meta)
	{
		$this->UniqName('page');
		$this->ui_meta=$meta;

		if (!is_array($meta))
			$this->ui_meta=array('title'=>$meta);
	}

	// other UI elements define their own Generate, but must also call GenerateContent
	function Generate()
	{
		global $POOF_URL;

		if ($_SERVER['REQUEST_METHOD']=='POST')
		{
			if ($this->HandlePost())
				return;
		}

		$styles=array('/css/bootstrap.css');
		$scripts=array('/js/bootstrap.js','/js/jquery.js');


		echo "<!DOCTYPE html>\n";
		echo "<html lang=\"en\">\n";

		echo "<head>\n";
		echo "<meta charset=\"utf-8\">";

		echo "<title>".htmlentities($this->ui_meta['title'])."</title>\n";
		foreach ($this->ui_meta as $name => $content)
			if ($name!='title')
				echo "<meta name=\"$name\" content=\"$content\">\n";

		foreach ($styles as $style)
			echo "<link href=\"{$POOF_URL}{$style}\" rel=\"stylesheet\">\n";

			

		echo "</head>";

		echo "<body>\n";

		echo "<div id=\"{$this->ui_name}\">\n";
		$this->GenerateContent();
		echo "</div>\n";

		foreach ($scripts as $script)
			echo "<script src=\"{$POOF_URL}{$script}\"></script>\n";

		echo "</body></html>\n";
	}

}
