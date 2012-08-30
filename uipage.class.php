<?php

class uiPage extends uiElement
{
	private $ui_meta;

	function __construct($meta)
	{
		parent::__construct();
		$this->ui_meta=$meta;

		if (!is_array($meta))
			$this->ui_meta=array('title'=>$meta);
	}

	// other UI elements define their own Generate, but must also call GenerateContent
	function __toString()
	{
		global $POOF_URL;

		if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
		{
			if ($this->HandlePost())
				return;
		}

		$styles=array('/css/bootstrap.css');
		$scripts=array('/js/bootstrap.js','/js/jquery.js');


		$output="<!DOCTYPE html>\n";
		$output.="<html lang=\"en\">\n";

		$output.="<head>\n";
		$output.="<meta charset=\"utf-8\">";

		$output.="<title>".htmlentities($this->ui_meta['title'])."</title>\n";
		foreach ($this->ui_meta as $name => $content)
			if ($name!='title')
				$output.="<meta name=\"$name\" content=\"$content\">\n";

		foreach ($styles as $style)
			$output.="<link href=\"{$POOF_URL}{$style}\" rel=\"stylesheet\">\n";

			
		foreach ($scripts as $script)
			$output.="<script src=\"{$POOF_URL}{$script}\"></script>\n";

		$output.="</head>";

		$output.="<body>\n";

		$output.="<div id=\"{$this->ui_name}\">";
		$output.=$this->GenerateContent();
		$output.="\n</div>\n";

/*
		foreach ($scripts as $script)
			$output.="<script src=\"{$POOF_URL}{$script}\"></script>\n";
*/

		$output.="</body></html>\n";
		return($output);
	}

}
