<?php

class uiEditable extends uiElement
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
            $this->fields=$this->DefaultFields($db);

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
        $id=0;
        foreach ($this->db->records() as $record)
        {
            $id++;
            $row='';
            foreach ($this->fields as $field => $header)
            {
                $row.=$this->Tag("td id=\"$id\"",htmlentities($record[$field]));
            }

            $body.=$this->Tag("tr id=\"$id\"",$row);
        }

        $table.=$this->Tag("tbody",$body);

        return($this->Tag($this->GenerateTag(),$table));
    }
    public function PostHandler($data)
    {
        $colspan=count($this->fields);
        echo "<td colspan=\"$colspan\">";
        echo "edit $colspan goes here ".time()." for ".print_r($data,true);
        echo "</td>";

        return(true);
    }
    public function PreGenerate($page)
    {
            /*
            alert(\$(this).text()+' id='+event.target.id);
            console.log(\"event=%o\",\$(this).context.id);
            */
        $url=$this->GetAction();
        $page->ReadyScript('editable',"\$(\"tbody\").on(\"click\",\"tr\",function(event){
            \$('#'+event.target.id).load('{$url}',{'edit':'form','id':event.target.id});
        });");
    }
}
