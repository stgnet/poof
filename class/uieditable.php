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

    public function enkey($record)
    {
        $encoded="k";
        foreach ($this->db->keys() as $field)
            $encoded.="|".urlencode($record[$field]);
        $encoded=bin2hex($encoded);
        return($encoded);
    }
    public function dekey($encoded)
    {
        $encoded=hex2bin($encoded);

        $keys=$this->db->keys();

        $values=explode('|',$encoded);

        $prefix=array_shift($values);
        if ($prefix!="k")
            return(null);

        $record=array();
        foreach ($values as $value)
        {
            $field=array_shift($keys);
            $record[$field]=urldecode($value);
        }
        return($record);
    }

    public function __toString()
    {
        $edit_delete_buttons= uiButton()
                ->AddClass("btn-small")
                ->AddAttr('name','edit')
                ->Add(uiIcon('pencil'))
            ." ".
            uiButton()
                ->AddClass("btn-small")
                ->AddAttr('name','delete')
                ->Add(uiIcon('trash'));

        $add_button=uiButton()
                ->AddClass("btn-small")
                ->Add(uiIcon('plus-sign'));

        $add_id=$this->db->guid();

        $keys=$this->db->keys();
        if (empty($keys) || count($keys)<1)
            Fatal("no keys provided");

        $row='';
        foreach ($this->fields as $field => $header)
            $row.=$this->Tag("th",htmlentities($header));

        $table=$this->Tag("thead",
            $this->Tag("tr",$row)
        );

        $body='';
        foreach ($this->db->records() as $record)
        {
            $row='';
            foreach ($this->fields as $field => $header)
            {
                $row.=$this->Tag("td",htmlentities($record[$field]));
            }
            $row.=$this->Tag("td",$edit_delete_buttons);

            $key=$this->enkey($record);
            $body.=$this->Tag("tr id=\"$key\"",$row);
        }
        $row='';
        foreach ($this->fields as $header)
            $row.=$this->Tag("td");
        $row.=$this->Tag("td width=\"80pt\"",$add_button);
        $body.=$this->Tag("tr id=\"{$add_id}\"",$row);

        $table.=$this->Tag("tbody",$body);

        return($this->Tag($this->GenerateTag(),$table));
    }
    public function PostHandler($data)
    {
        $save_cancel_buttons=uiButton()
                ->AddClass("btn-small")
                ->AddAttr('name','save')
                ->Add(uiIcon('ok'))
            ." ".
            uiButton()
                ->AddClass("btn-small")
                ->AddAttr('name','cancel')
                ->Add(uiIcon('remove'));

        $colspan=count($this->fields);
        $edit="edit: ".print_r($data,true)." | ".print_r($this->dekey($data['key']),true);

        echo $this->Tag("td colspan=\"$colspan\"",$edit);
        echo $this->Tag("td width-\"80pt\"",$save_cancel_buttons);

        return(true);
    }
    public function PreGenerate($page)
    {
        $url=$this->GetAction();
        $class=$this->ui_id;
        $script="\$('#$class').on('click',function(event){
            var btn=event.target;
            if (btn.tagName!=\"BUTTON\")
            {
                btn=btn.parentNode;
                if (btn.tagName!=\"BUTTON\")
                    return(true);
            }
            var row=btn.parentNode.parentNode;
            \$('#'+row.id).load('{$url}',{'button':btn.name,'key':row.id});
            console.log(\"btn=%o\",btn);
        });";

        $page->ReadyScript($class,$script);
    }
}
