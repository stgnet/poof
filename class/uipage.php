<?php

class uiPage extends uiElement
{
	private $ui_meta;
	private $ui_styles;
	private $ui_prescripts;
	private $ui_postscripts;
	private $ui_headscripts;
	private $ui_readyscripts;

	function __construct($meta)
	{
		parent::__construct();
		$this->ui_meta=$meta;

		$this->ui_styles=array('bootstrap.css');
		$this->ui_prescripts=array();
		// jquery always goes first!
		$this->ui_postscripts=array('jquery.js','bootstrap.js');
		$this->ui_headscripts=array();
		$this->ui_readyscripts=array();

		if (!is_array($meta))
			$this->ui_meta=array('title'=>$meta);
	}
	function Stylesheet($name,$file)
	{
		$this->ui_styles[$name]=$file;
	}
	function PreScript($name,$file)
	{
		$this->ui_prescripts[$name]=$file;
	}
	function PostScript($name,$file)
	{
		$this->ui_postscripts[$name]=$file;
	}
	function HeadScript($name,$code)
	{
		$this->ui_headscripts[$name]=$code;
	}
	function ReadyScript($name,$code)
	{
		$this->ui_readyscripts[$name]=$code;
	}
	private function pathfix($default,$path)
	{
		if ($path[0]=='/')
			return($path);
		return($default."/".$path);
	}
	function GenerateStyles()
	{
		global $POOF_URL;

		$output='';

		foreach ($this->ui_styles as $style)
			$output.=$this->Tag("link href=\"".
				$this->pathfix("$POOF_URL/css",$style).
				"\" rel=\"stylesheet\"");

		return($output);
	}
	function GeneratePreScripts()
	{
		global $POOF_URL;
		$output='';
		$head='';

		foreach ($this->ui_prescripts as $script)
			$output.=$this->Tag("script src=\"".
				$this->pathfix("$POOF_URL/js",$script).
				"\"");

		$head='';
		foreach ($this->ui_headscripts as $code)
			$head.=" ".$code."\n";

		if (!empty($head))
			$output.=$this->Tag("script type=\"text/javascript\"",$head);

		return($output);
	}
	function GeneratePostScripts()
	{
		global $POOF_URL;
		$output='';

		foreach ($this->ui_postscripts as $script)
			$output.=$this->Tag("script src=\"".
				$this->pathfix("$POOF_URL/js",$script).
				"\"");

		// active the bootstrap js components
		$ready='';
		foreach ($this->ui_readyscripts as $code)
			$ready.=" ".$code."\n";
/*
	\$('.nav-tabs').button();
	\$('#{$this->ui_name}').bind('contextmenu',function(e){
		if (e.ctrlKey)
		{
			alert('right click '+e.toElement.id);
			console.log(\"rightclick=%o\",e);
			return false;
		}
	})
*/
		if (!empty($ready))
		$output.=$this->Tag("script type=\"text/javascript\"",
			"\$(document).ready(function(){\n".
			$ready.
			"});\n"
		);
		return($output);
	}
	function GenerateMeta()
	{
		$output='';

		foreach ($this->ui_meta as $name => $content)
			if ($name!='title')
				$output.=$this->Tag("meta name=\"$name\" content=\"$content\"");
				//$output.=$this->Indent()."<meta name=\"$name\" content=\"$content\">";

		return($output);
	}

	function __toString()
	{

		if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
		{
			if ($this->HandlePost())
				return;
		}


		// allow tree elements to pass scripts/css up to page generator
		$this->PreGenerateWalk($this);

		return("<!DOCTYPE html>".
			$this->Tag("html lang=\"en\"",
				$this->Tag("head",
					$this->Tag("title",htmlentities($this->ui_meta['title'])).
					$this->Tag("meta charset=\"utf-8\"").
					$this->GenerateMeta().
					$this->GenerateStyles().
					$this->GeneratePreScripts()
				).
				$this->Tag("body",
					$this->Tag($this->GenerateTag(),
						$this->GenerateContent()
					)
				).
					$this->GeneratePostScripts()
			)
		);
	}

}
