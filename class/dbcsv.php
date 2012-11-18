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

    public function keys()
    {
        Fatal("Not implemented");
    }
    public function fields()
    {
        global $dbcsv_daemon;
        //return(array_keys($this->table[0]));
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
        Fatal("Not implemented");
    }
    public function unary($where)
    {
        Fatal("Not implemented");
    }
    public function update($record,$where=NULL)
    {
        Fatal("Not implemented");
    }
    public function insert($record)
    {
        Fatal("Not implemented");
    }
    public function delete($record=NULL,$where=NULL)
    {
        Fatal("Not implemented");
    }

}
