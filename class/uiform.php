<?php

class uiform extends uiElement
{
    protected $record;
    protected $fields;
    protected $style;
    protected $target;

    public function __construct($fields=false,$record=false,$style=false)
    {
        parent::__construct();
        $this->target=false;
        $this->style=$style;
        $this->ui_tag="form";
        if ($style) {
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
        else {
            $this->fields=array();
            if ($record) foreach ($record as $name => $value) {
                // have to stupidly presume text field
                $this->fields[$name]=array('type'=>"text");
            }
        }
        // and insure that all keys exist
        foreach ($this->fields as $name => $pairs) {
            if (!isset($this->record[$name]))
                $this->record[$name]='';
        }

        // insure name is in attributes
        foreach ($this->fields as $name => $pairs)
        {
            if (!isset($pair['name']))
                $pair['name']=$name;
        }

        // add each field to this form

        foreach ($this->fields as $name => $attributes) {
            $type="text";
            if (!empty($attributes['type']))
                $type=$attributes['type'];

            $class="uiInput_$type";
            $this->Add($class($attributes)->SetName($name));
        }

    }
    public function OnSubmit($element)
    {
        $this->target=$element;
        return($this);
    }
    public function PreGenerate($page)
    {
        if (!$this->target) return;

        $form='#'.$this->ui_id;
        $url=$this->GetAction();
        $target='#'.$this->target->ui_id;
        $page->ReadyScript('form-onsubmit-'.$this->ui_id,"
            \$('{$form}').submit(function(){
                \$.ajax({
                    type: \"POST\",
                    url: \"$url\",
                    data: \$(this).serialize(),
                    success: function(data) {
                        \$('{$target}').empty().append(data);
                    },
                    error: function(xhr) {
                        alert(xhr.status+\" \"+xhr.statusText+\": \"+xhr.responseText);
                    }
                });
                return false;
            });");

                //\$('{$target}').load('{$url}',$('{$form}').serializeArray());
    }
    public function __toString()
    {
        $output='';
        foreach ($this->ContentArray() as $element) {
            $desc=$element->GetDescription();

            if ($this->style=='inline') {
                // place no divs
                if ($desc)
                    $element->SetInlineDescription($desc);
                $output.=$element;
                continue;
            }

            if ($this->style=='search') {
                if ($desc)
                    $element->SetInlineDescription($desc);
            }

            $for=$element->ui_id;

            $group='';
            if ($this->style!='search')
                $group=$this->Tag("label class=\"control-label\" for=\"$for\"",$desc);
            if ($this->style=='horizontal') {
                $group.=$this->Tag("div class=\"controls\"",$element);
                $output.=$this->Tag("div class=\"control-group\"",$group);
            } else
                $output.=$group.$element;
        }

        return($this->Tag($this->GenerateTag(),$output));
    }
}
