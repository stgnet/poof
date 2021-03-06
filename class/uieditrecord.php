<?php

/**
 * single record editor - use with dbWhere
 * @package poof
*/
class uiEditRecord extends uiForm
{
    protected $fields;
    private $db;
    private $posturl;

    /**
     * create field that edits database
     * @param object $db database
     * @param array $fields optional list of fields to edit
     */
    public function __construct($db,$fields=NULL)
    {
        $this->posturl=false;

        if (!($db instanceof dbWhere))
            Fatal("uiEditRecord requires dbWhere");

        if ($fields)
            $this->fields=$fields;
        else
            $this->fields=$db->fields(true);

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

        $fields_buttons=array_merge($this->fields,array(
            'submit'=>array('type'=>"button",'value'=>"SAVE")
        ));

        $record=$db->Lookup();
        parent::__construct($fields_buttons,$record,"horizontal");

        $target=uiDiv()->Add("TARGET");
        $this->Add($target);
        $this->OnSubmit($target);
    }
    public function PostUrl($url)
    {
        $this->posturl=$url;
        return($this);
    }

    public function PostHandler($data)
    {
        //echo uiPre(print_r($data,true));
        $this->db->update($data);
        echo uiBadge("success")->Add("Saved");
        if ($this->posturl)
        echo "<script>setTimeout(function(){
            window.location.href=\"{$this->posturl}\";
        },3000);</script>";

        return(true);
    }

}
