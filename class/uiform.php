<?php

class uiForm extends uiElement
{
	protected $record;
	protected $fields;

	function __construct($fields=false,$record=false,$style=false)
	{
		parent::__construct();
		$this->ui_tag="form";
		if ($style)
		{
			if (substr($style,0,5)!="form-")
				$style="form-".$style;
			$this->ui_class=$style;
		}

		if ($record && is_array($record))
			$this->record=$record;
		else
			$this->record=array();

		// generate field list from record list if not supplied
		if ($fields)
			$this->fields=$fields;
		else
		{
			$this->fields=array();
			if ($record) foreach ($record as $name => $value)
			{
				// have to stupidly presume text field
				$this->fields[$name]=array('type'=>"text");
			}
		}
		// and insure that all keys exist
		foreach ($this->fields as $name => $pairs)
		{
			if (!isset($this->record[$name]))
				$this->record[$name]='';
		}
	}

	function __toString()
	{
		$input_attr=array('type','size','readonly','placeholder');
		$output='';
		foreach ($this->fields as $fieldname => $attributes)
		{
			$tag="input id=\"$fieldname\" name=\"$fieldname\"";
			if ($this->record[$fieldname])
				$tag.=" value=\"{$this->record[$fieldname]}\"";

			foreach ($attributes as $attrname => $attrvalue)
			{
				if (in_array($attrname,$input_attr))
					$tag.=" $attrname=\"$attrvalue\"";
			}
			$output.=$this->Tag($tag);
		}
		return($this->Tag($this->GenerateTag(),$output));
	}

}
