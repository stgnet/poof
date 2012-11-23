<?php
class dbCsv extends pfBase
{
    //private $table;
    private $path;

    /*
    private function ReadCsv($file)
    {
        $this->table=array();

        $fp=fopen($file,"r");
        $header=fgetcsv($fp);
        while ($row=fgetcsv($fp)) {
            $record=array();
            $index=0;
            foreach ($row as $data) {
                if (empty($header[$index]))
                    $header[$index]="COL$index";
                $record[$header[$index]]=$data;
                $index++;
            }
            $this->table[]=$record;
        }
    }
    */

    public function __construct($file)
    {
        global $dbcsv_daemon;

        if ($file[0]=="/")
            $this->path=$file;
        else
            $this->path=getcwd()."/".$file;

        $dbcsv_daemon=pfDaemon("dbcsv");
    }

    public function SetFields($fields)
    {
        global $dbcsv_daemon;
        $dbcsv_daemon->SetFields($this->path,$fields);
        return($this);
    }
    public function SetKey($key)
    {
        global $dbcsv_daemon;
        $dbcsv_daemon->SetKey($this->path,$key);
        return($this);
    }
    public function keys()
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->keys($this->path));
    }
    public function fields()
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->fields($this->path));
    }
    public function escape($data)
    {
        Fatal("Not implemented");
    }
    public function records($where=NULL,$limit=NULL)
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->records($this->path,$where));
    }
    public function lookup($where)
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->lookup($this->path,$where));
    }
    public function unary($where)
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->unary($this->path,$where));
    }
    public function update($record,$where=NULL)
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->update($this->path,$record,$where));
    }
    public function insert($record)
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->insert($this->path,$record));
    }
    public function delete($record=NULL)
    {
        global $dbcsv_daemon;
        return($dbcsv_daemon->delete($this->path,$record));
    }

}
