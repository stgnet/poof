<?php

// new uiElement base class replaces ui and uibase classes

class uiElement 
{
	protected $ui_name;
	protected $ui_class;
	private $ui_parent;
	private $ui_contents;

	function __construct()
	{
		$this->UniqName('element');
		$this->ui_class=false;
	}
	function UniqName($prefix=NULL)
	{
		if (!$prefix)
			$prefix=substr(strtolower(get_class($this)),2);
		$count='POOF_UI_DIV_'.$prefix;
		if (empty($GLOBALS[$count]))
			$GLOBALS[$count]=1;
		$this->ui_name=$prefix.$GLOBALS[$count]++;
	}

	function Add()
	{
		$args=func_get_args();
		foreach ($args as $obj)
		{
			if (!$obj instanceof uiElement)
				Fatal("uiElement::Add(".get_class($obj)."): incompatible object not based on uiElement!");

			if ($obj->ui_parent)
				Fatal("uiElement::Add({$obj->ui_name}): already added to {$obj->ui_parent->ui_name}");

			$this->ui_contents[]=$obj;
			$obj->ui_parent=&$this;
		}

		// Add() returns this to allow convenient tree building
		return($this);
	}

	function GetPath()
	{
		// the SCRIPT_NAME is pre-stripped URL matching actual php executed
		$path=$_SERVER['SCRIPT_NAME'];

		// when operating in a sugar mode, the module path needs to be artificially added
		if (!empty($GLOBALS['sugar_config']) && !empty($_REQUEST['module']))
			$path=dirname($path)."/modules/".$_REQUEST['module']."/index.php";

		return($path);
	}

	// this returns a full URL to the php script, plus an extended path
	// that matches the UI element tree names
	function GetAction()
	{
		$action=$this->GetPath();
		if (substr($action,0,2)=='//')
			$action=substr($action,1);

		// construct element stack
		$path='';
		$walk=&$this;
		while ($walk)
		{
			$path="/".$walk->ui_name.$path;
			$walk=$walk->ui_parent;
		}
		$action.=$path;

		return($action);
	}

	function DefaultFields($db)
	{
		// obtain list of fields for table/edit from database itself
		$fields=array();
		foreach ($db->fields() as $field)
		{
			$name=str_replace(array('-','_')," ",$field);
			$fields[$field]=ucwords($name);
		}
		return($fields);
	}
	function HandlePost()
	{
		global $_FILES;

		// iterate the tree and locate the element that matches the action
		$action=$this->GetAction();
		$self=$_SERVER['PHP_SELF'];

		if ($action==$self)
		{
			if (!method_exists($this,"POST"))
				Fatal("class element does not implement POST");

			if (empty($_FILES))
				return($this->POST($_POST));
			else
				return($this->POST($_POST,$_FILES));
		}

		$path=substr($_SERVER['PHP_SELF'],strlen($action)+1);
		if (!$path) Fatal("unable to determine path to post element");

		$subpath=explode('/',$path);
		$post_is_for=$subpath[0];

		foreach ($this->ui_contents as $element)
		{
			if ($element->ui_name==$post_is_for)
			{
				return($element->HandlePost());
			}
		}
		Fatal("unable to locate post element '$post_is_for'");
	}

	function GenerateContent()
	{
		global $POOF_UI_DEBUG;

		if (empty($this->ui_parent))
		{
			if ($_SERVER['REQUEST_METHOD']=='POST')
			{
				if ($this->HandlePost())
					return;
			}
		}

		$output='';
		if ($this->ui_contents) foreach ($this->ui_contents as $element)
		{

			if (!$element->ui_name) Fatal("UI Element Name not set");

			// debugging aid:
			if (!empty($POOF_UI_DEBUG))
				$output.="<div style=\"margin: 10px; border: 3px #ccc solid;box-shadow: 5px 5px 2px #888 ;\"><div style=\"background-color: #ccc;\">{$element->ui_name}  ({$element->ui_class})</div>";

			$output.="<div id=\"{$element->ui_name}\"";
			if ($element->ui_class)
				$output.=" class=\"{$element->ui_class}\"";
			$output.=">\n";

			//$element->Generate();
			$output.=$element;

			$output.="</div>\n";

			if (!empty($POOF_UI_DEBUG))
				$output.="</div>\n";

		}
		return($output);
	}

	// generate output, but also content from child elements
	function __toString()
	{
		// this base class doesn't actually generate output,
		// so just call call the elements
		return($this->GenerateContent());
	}
}
