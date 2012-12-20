<?php

define("BTNCOL_WIDTH","125pt");

/**
 * Table based database editor
 * @package poof
*/
class uiEditable extends uiElement
{
    private $fields;
    private $fieldnames;
    private $db;

    /**
     * create table that edits database
     * @param object $db database
     * @param array $fields optional list of fields to edit
     */
    public function __construct($db,$fields=NULL)
    {
        parent::__construct();
        $this->ui_tag="table";
        $this->AddClass("table");

        if ($fields)
            $this->fields=$fields;
        else
            $this->fields=$db->fields(true);

        $this->fieldnames=$this->FieldsWithNames($this->fields);

        foreach ($db->keys() as $key)
        {
            if (!empty($this->fields[$key]))
                $this->fields[$key]['type']="key";
            /*
            else
                $this->fields[$key]=array($key=>array('type'=>"key"));
            */
        }

        $this->db=$db;
    }

    private function enkey($record)
    {
        $encoded="k";
        foreach ($this->db->keys() as $field)
            $encoded.="|".urlencode($record[$field]);
        $encoded=bin2hex($encoded);
        return($encoded);
    }
    private function dekey($encoded)
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
        $edit_delete_buttons=uiIconButton('pencil','edit')." ".
            uiIconButton('trash','delete');

        $add_button=uiIconButton('plus-sign','add');

        $add_id="_".$this->db->guid();

        $keys=$this->db->keys();
        if (empty($keys) || count($keys)<1)
            Fatal("no keys provided");

        $row='';
        foreach ($this->fieldnames as $field => $header)
        {
            $row.=$this->Tag("th",htmlentities($header));
        }
        $row.=$this->Tag("th","");

        $table=$this->Tag("thead",
            $this->Tag("tr",$row)
        );

        $body='';
        foreach ($this->db->records() as $record)
        {
            $row='';
            foreach ($this->fieldnames as $field => $header)
            {
                $row.=$this->Tag("td",htmlentities($record[$field]));
            }
            $row.=$this->Tag("td",$edit_delete_buttons);

            $key=$this->enkey($record);
            $body.=$this->Tag("tr id=\"$key\"",$row);
        }
        $row='';
        foreach ($this->fieldnames as $header)
            $row.=$this->Tag("td");
        $row.=$this->Tag("td width=\"".BTNCOL_WIDTH."\"",$add_button);
        $body.=$this->Tag("tr id=\"{$add_id}\"",$row);

        $table.=$this->Tag("tbody",$body);

        return($this->Tag($this->GenerateTag(),$table));
    }
    public function PostHandler($data)
    {
        $edit_delete_buttons=uiIconButton('pencil','edit')." ".
            uiIconButton('trash','delete');

        $save_cancel_buttons=uiIconButton('ok','save')." ".
            uiIconButton('remove','cancel');

        $add_cancel_buttons=uiIconButton('ok','savenew')." ".
            uiIconButton('remove','cancelnew');

        $colspan=count($this->fieldnames);

        if (empty($data['button']) || empty($data['key']) || empty($data['form']))
            Fatal("post data missing fields");

        $keys=false; // no key for new record
        if ($data['key'][0]!="_")
        {
            $keys=$this->dekey($data['key']);
            if (!$keys)
                Fatal("post key did not decode");
        }

        if ($data['button']=="add")
        {
            echo $this->Tag("td colspan=\"$colspan\"",uiForm($this->fields,array(),"inline"));
            echo $this->Tag("td width=\"".BTNCOL_WIDTH."\"",$add_cancel_buttons);
            return(true);
        }
        if ($data['button']=="cancelnew")
        {
            // user cancelled adding, put the regular buttons back
            $add_button=uiIconButton('plus-sign','add');
            echo $this->Tag("td colspan=\"$colspan\"","");
            echo $this->Tag("td width=\"".BTNCOL_WIDTH."\"",$add_button);
            return(true);
        }
        if ($data['button']=="savenew")
        {
            $record=arQuery($data['form']);

            // insert the record, get updated copy with key(s) back
            $record=$this->db->insert($record);

            // redraw the record in table format
            $key=$this->enkey($record);
            foreach ($this->fieldnames as $field => $header)
                echo $this->Tag("td",htmlentities($record[$field]));
            echo $this->Tag("td",$edit_delete_buttons);

            /*
            $add_id="_".$this->db->guid();
            $add_button=uiIconButton('plus-sign','add');
            $row='';
            foreach ($this->fieldnames as $header)
                $row.=$this->Tag("td");
            $row.=$this->Tag("td width=\"".BTNCOL_WIDTH."\"",$add_button);
            $body.=$this->Tag("tr id=\"{$add_id}\"",$row);
            */

            // with insert not working, just reload the whole page
            echo "<script type=\"text/javascript\">
            location.reload(true);
            </script>";

            //\$('#{$data['key']}').id='$key';

            //\$('#{$data['key']}').after('$row');

            return(true);
        }
        if ($data['button']=="edit")
        {
            // get the record
            $record=$this->db->lookup($keys);
            if (!$record)
                Fatal("did not locate record");

            // output the edit form:

            // can't put td's around inputs as it breaks serialize()
            // TODO: an improved js serialize that can pull from arbitrary input fields
            // in the meantime: columns aren't aligned during add/edit
            echo $this->Tag("td colspan=\"$colspan\"",uiForm($this->fields,$record,"inline"));
            //echo uiForm($this->fields,$record,"inline");
            echo $this->Tag("td width=\"".BTNCOL_WIDTH."\"",$save_cancel_buttons);
            return(true);
        }
        if ($data['button']=='save' || $data['button']=='cancel')
        {
            $record=arQuery($data['form']);

            // copy keys into record in case form missing or mangled them
            foreach ($keys as $key => $value)
                $record[$key]=$value;

            // save the record
            if ($data['button']=='save')
                $this->db->update($record);

            // grab it again just to make sure
            $record=$this->db->lookup($record);
            if (!$record)
                Fatal("unable to locate record");

            // redraw the record in table format
            foreach ($this->fieldnames as $field => $header)
                echo $this->Tag("td",htmlentities($record[$field]));
            echo $this->Tag("td",$edit_delete_buttons);

            return(true);
        }
        if ($data['button']=='delete')
        {
            // lookup the record to make sure it's still there
            $record=$this->db->lookup($keys);
            if (!$record)
                Fatal("did not locate record");

            // delete the record
            $this->db->delete($record);

            // redraw it (temporarily) as deleted
            /*
            echo $this->Tag("td colspan=\"$colspan\"","deleted");
            echo $this->Tag("td","");
            */

            // ask javascript to delete the row
            echo "<script type=\"text/javascript\">
            \$('#{$data['key']}').remove();
            </script>";

            return(true);
        }
        Fatal("button value not recognized");
    }
    public function PreGenerate($page)
    {
        $url=$this->GetAction();
        $class=$this->ui_id;
        $script="\$('#$class').on('click',function(event){
            var btn=event.target;
            // click target could be icon in button
            // work backwords to locate button
            if (btn.tagName!='BUTTON')
            {
                btn=btn.parentNode;
                if (btn.tagName!='BUTTON')
                    return(true);
            }
            // confirm deletes first
            if (btn.name=='delete')
            {
                if (!confirm('Delete this record?'))
                    return(true);
            }
            // locate the tr tag two levels above
            var row=btn.parentNode.parentNode;
            if (row.tagName!='TR')
            {
                console.log('wrong row tag');
                return(true);
            }
            // locate the form if present
            var formdata=false;
            var form=row.firstElementChild;
            if (form && form.tagName!=='FORM')
                form=form.firstElementChild;
            if (form && form.tagName=='FORM')
            {
                // force a submit on the form
                \$(form).submit(function(){
                    formdata=$(this).serialize();
                    console.log('have form data='+formdata);
                    return false;
                });
                \$(form).submit();
            }
            // reload the row via post
            \$('#'+row.id).load('{$url}',{'button':btn.name,'key':row.id,'form':formdata});
            return(true);
        });";

        $page->ReadyScript($class,$script);
    }
}
