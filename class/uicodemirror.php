<?php

class uiCodeMirror extends uiElement
{
	function __construct($text)
	{
		parent::__construct();
		$this->ui_tag="textarea";
		$this->ui_text=$text;
	}
	function PreGenerate($page)
	{
		$page->Stylesheet('codemirror',"codemirror.css");
		$page->PreScript('codemirror',"codemirror/codemirror.js");
		$page->PreScript('codemirror-xml',"codemirror/mode/xml/xml.js");
		$page->PreScript('codemirror-js',"codemirror/mode/javascript/javascript.js");
		$page->PreScript('codemirror-css',"codemirror/mode/css/css.js");
		$page->PreScript('codemirror-clike',"codemirror/mode/clike/clike.js");
		$page->PreScript('codemirror-php',"codemirror/mode/php/php.js");
		$page->ReadyScript('codemirror-'.$this->ui_name,"
			var editor = CodeMirror.fromTextArea(document.getElementById(\"{$this->ui_name}\"), {
				lineNumbers: true,
				matchBrackets: true,
				mode: \"application/x-httpd-php\",
				indentUnit: 4,
				indentWithTabs: true,
				enterMode: \"keep\",
				tabMode: \"shift\"
			});
		");
	}
}
