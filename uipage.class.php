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
	function GenerateStyles()
	{
		global $POOF_URL;
		$styles=array('/css/bootstrap.css');

		$output='';

		foreach ($styles as $style)
			$output.=$this->Tag("link href=\"{$POOF_URL}{$style}\" rel=\"stylesheet\"");
			//$output.=$this->Indent()."<link href=\"{$POOF_URL}{$style}\" rel=\"stylesheet\">";

		return($output);
	}
	function GenerateScripts()
	{
		global $POOF_URL;
		// jquery always goes first!
		$scripts=array('/js/jquery.js','/js/bootstrap.js');
		$output='';

		foreach ($scripts as $script)
			$output.=$this->Tag("script src=\"{$POOF_URL}{$script}\"");

		// active the bootstrap js components
		$output.="<script type=\"text/javascript\">
\$(document).ready(function(){
	\$('.dropdown-toggle').dropdown();
	\$('a[rel=\"popover\"]').popover();
	\$('a[rel=\"tooltip\"]').tooltip();
	\$('.nav-tabs').button();
	\$('.carousel').carousel();
	\$('.collapse').collapse();
});
</script>";
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

	// other UI elements define their own Generate, but must also call GenerateContent
	function __toString()
	{

		if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
		{
			if ($this->HandlePost())
				return;
		}

		return("<!DOCTYPE html>".
			$this->Tag("html lang=\"en\"",
				$this->Tag("head",
					$this->Tag("title",htmlentities($this->ui_meta['title'])).
					$this->Tag("meta charset=\"utf-8\"").
					$this->GenerateMeta().
					$this->GenerateStyles()
				).
				$this->Tag("body",
					$this->Tag($this->GenerateTag(),
						$this->GenerateContent()
					)
				).
					$this->GenerateScripts()
			)
		);
	}

}
