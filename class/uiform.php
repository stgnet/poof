<?php

class uiForm extends uiElement
{
	protected $record;
	protected $fields;
	protected $style;

	function __construct($fields=false,$record=false,$style=false)
	{
		parent::__construct();
		$this->style=$style;
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

		// add each field to this form

		foreach ($this->fields as $name => $attributes)
		{
			$type="text";
			if (!empty($attributes['type']))
				$type=$attributes['type'];

			$class="uiInput_$type";
			$this->Add($class($attributes));
		}
		
	}
	function __toString()
	{
		$output='';
		foreach ($this->ContentArray() as $element)
		{
			$desc=$element->GetDescription();

			if ($this->style=='inline')
			{
				if ($desc)
					$element->AddAttr('placeholder',$desc);
				$output.=$element;
				continue;
			}
			$for=$element->ui_name;

			$group=$this->Tag("label class=\"control-label\" for=\"$for\"",$desc);
			$group.=$this->Tag("div class=\"controls\"",$element);

			$output.=$this->Tag("div class=\"control-group\"",$group);
		}
		return($this->Tag($this->GenerateTag(),$output));
	}

/*
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
			$group =$this->Tag("label class=\"control-label\" for=\"$fieldname\"",$fieldname);
			$group.=$this->Tag("div class=\"controls\"",$this->Tag($tag));
			$output.=$this->Tag("div class=\"control-group\"",$group);
		}
		return($this->Tag($this->GenerateTag(),$output));
	}
*/

}
