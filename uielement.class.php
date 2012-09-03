<?php

// new uiElement base class replaces ui and uibase classes

class uiElement 
{
	private $ui_parent;
	private $ui_contents;
	protected $ui_name;
	protected $ui_tag;
	protected $ui_class;
	protected $ui_style;
	protected $ui_attr;
	protected $ui_html;
	protected $ui_text;

	function __construct()
	{
		$id=substr(strtolower(get_class($this)),2);
		$counter="POOF_UI_DIV_".$id;
		if (empty($GLOBALS[$counter]))
			$GLOBALS[$counter]=1;
		$this->ui_name=$id.$GLOBALS[$counter]++;
		$this->ui_tag="div";
		$this->ui_class=false;
		$this->ui_style=false;
		$this->ui_attr=false;
		$this->ui_html=false;
		$this->ui_text=false;
	}
	function SetTag($tag)
	{
		$this->ui_tag=$tag;
	}
	function AddClass($class)
	{
		if (empty($this->ui_class))
			$this->ui_class=$class;
		else
			$this->ui_class.=" ".$class;
		// chaining
		return($this);
	}
	function AddStyle($style)
	{
		if (empty($this->ui_style))
			$this->ui_style=$style;
		else
			$this->ui_style.=" ".$style;
		return($this);
	}

	function Add()
	{
		$args=func_get_args();
		foreach ($args as $obj)
		{
			if (is_string($obj))
				$obj=uiHtml($obj);

			if (!$obj instanceof uiElement)
				Fatal("uiElement::Add(".get_class($obj)."): incompatible object not based on uiElement! ");

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

	function Indent($adjust=0)
	{
		global $POOF_UI_LEVEL;

		$indention="    ";

		if ($adjust<=0)
			$POOF_UI_LEVEL+=$adjust;
		$output="\n".str_repeat($indention,$POOF_UI_LEVEL);
		if ($adjust>0)
			$POOF_UI_LEVEL+=$adjust;
		return($output);
	}
	function Tag($tag,$contents=false)
	{
		$untag=explode(' ',$tag)[0];

		if (empty($contents) && $untag!="script" && $untag!="i")
			return($this->Indent()."<$tag />");

		if (!substr_count($contents,"\n") || $untag=="pre")
			return($this->Indent()."<$tag>$contents</$untag>");

		return($this->Indent(1)."<$tag>$contents".$this->Indent(-1)."</$untag>");
	}
	function GenerateTag()
	{
		$tag="{$this->ui_tag} id=\"$this->ui_name\"";
		if ($this->ui_class)
			$tag.=" class=\"{$this->ui_class}\"";
		if ($this->ui_style)
			$tag.=" style=\"{$this->ui_style}\"";
		if ($this->ui_attr)
			$tag.=" ".$this->ui_attr;
		return($tag);
	}
	function GenerateContentArray()
	{
		// just like GenerateContent, except that it
		// returns an array for each subelement (first level only)
		// so that caller can wrap li tags or such
		$content=array();
		if ($this->ui_contents) foreach ($this->ui_contents as $element)
			$content[]=$this->Tag($element->GenerateTag(),$element);
		return($content);
	}
	function GenerateContent()
	{
		global $POOF_UI_DEBUG;

		if (empty($this->ui_parent))
		{
			if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST')
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
			if (!empty($POOF_UI_DEBUG) || !empty($_GET['debug']))
				$output.="<div style=\"margin: 10px; border: 3px #aaa solid;box-shadow: 5px 5px 2px #444 ;\"><div style=\"background-color: #aaa;\">{$element->ui_name}  ({$element->ui_class})</div>\n";

			$output.=$this->Tag($element->GenerateTag(),$element);

			if (!empty($POOF_UI_DEBUG) || !empty($_GET['debug']))
				$output.="</div>\n";

		}
		return($output);
	}

	// generate output, but also content from child elements
	// child classes should overload this generate their own html output
	function __toString()
	{
		// this base class doesn't actually generate output,
		// so just call call the elements
		return($this->ui_html.htmlentities($this->ui_text).$this->GenerateContent());
	}
}
