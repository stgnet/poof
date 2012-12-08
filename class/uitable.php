<?php

class uiTable extends uiElement
{
    public $fields;
    public $db;

    public function __construct($db,$fields=NULL)
    {
        parent::__construct();
        $this->ui_tag="table";
        $this->AddClass("table");

        if ($fields)
            $this->fields=$fields;
        else
            $this->fields=$db->fields(); //$this->DefaultFields($db);
        $this->fields=$this->FieldsWithNames($this->fields);

        $this->db=$db;
    }

    public function __toString()
    {
        $row='';
        foreach ($this->fields as $field => $header)
            $row.=$this->Tag("th",htmlentities($header));

        $table=$this->Tag("thead",
            $this->Tag("tr",$row)
        );

        $body='';
        foreach ($this->db->records() as $record) {
            $row='';
            foreach ($this->fields as $field => $header)
            {
                if (!array_key_exists($field,$record))
                    $text="Error: $field not found";
                else
                    $text=$record[$field];
                $row.=$this->Tag("td",htmlentities($text));
            }

            $body.=$this->Tag("tr",$row);
        }

        $table.=$this->Tag("tbody",$body);

        return($this->Tag($this->GenerateTag(),$table));
    }
}
