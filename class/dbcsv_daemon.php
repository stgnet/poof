<?php

require_once(dirname(dirname(__FILE__))."/poof.php");

/*
    Daemon (background server process with IPC) that
    handles CSV files as a database
*/

class dbcsv_file extends dbBase
{
    private $path; // full path to file
    private $stat; // stat() of file when last read
    public $table; // array of records (record = array of field=>value)
    private $last; // last time file read
    public $fields; // array of fields (can be expanded beyond last read)
    public $header; // array of fields on last read
    private $writewhen; // when non-zero, when to write changed file
    public $key; // key field (unique id)
    public $keyhigh; // highest value of key field

    public function __construct($path)
    {
        $this->writewhen=false;
        $this->key=false;
        $this->keyhigh=false;
        $this->fields=array();
        $this->header=array();
        $this->path=$path;
        $this->readfile();
        return($this);
    }
    function readfile()
    {
        $this->last=time();
        $this->table=array();

        if (!file_exists($this->path))
        {
            $this->stat=array();
            return;
        }

        $this->keyhigh=false;

        $fp=fopen($this->path,"r");
        if (!$fp)
        {
           // can't open file
           Warning("can't open file ".$this->path);
           return;
        }
        $this->stat=stat($this->path);

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

            if ($this->key && !empty($record[$this->key]))
                if ($this->keyhigh<$record[$this->key])
                    $this->keyhigh=$record[$this->key];
        }

        foreach ($header as $name)
            if (!in_array($name,$this->fields))
                $this->fields[]=$name;

        $this->header=$header;
        fclose($fp);
    }
    function writefile()
    {
        $fp=fopen($this->path,"w");
        if (!$fp)
        {
           // can't open file
           Warning("can't open file ".$this->path);
           return;
        }
        $this->stat=stat($this->path);

        fputcsv($fp,$this->fields);
        foreach ($this->table as $record)
        {
            $ordered=array();
            foreach ($this->fields as $field)
                $ordered[]=(array_key_exists($field,$record)?$record[$field]:'');
            fputcsv($fp,$ordered);
        }
        fclose($fp);
        $this->writewhen=false;
    }
    function write()
    {
        // set write flag if not already
        if (!$this->writewhen)
            $this->writewhen=time()+2; // update file every 3 secs when changing
    }
    function _Process()
    {
        if ($this->writewhen && time()>$this->writewhen)
            $this->writefile();
    }
    function addrecord($record)
    {
        // if key field defined, insure it is unique
        if ($this->key && empty($record[$this->key]))
        {
            if (empty($this->keyhigh) || is_numeric($this->keyhigh))
            {
                $this->keyhigh=1+$this->keyhigh;
                $record[$this->key]=$this->keyhigh;
            }
            else
            {
                $record[$this->key]=$this->guid();
            }
        }
        if (!empty($record[$this->key]) && $this->keyhigh<$record[$this->key])
            $this->keyhigh=$record[$this->key];

        // is there a column in record that is not in last read header?
        foreach ($record as $field => $value)
        {
            if (!in_array($field,$this->header))
            {
                // can't add single record, must rewrite entire file
                $this->table[]=$record;
                $this->write();
                return($record);
            }
        }

        $ordered=array();
        foreach ($this->header as $field)
                $ordered[]=(array_key_exists($field,$record)?$record[$field]:'');

        $fp=fopen($this->path,"a");
        fputcsv($fp,$ordered);
        fclose($fp);

        // insure record added has all current fields
        foreach ($this->header as $field)
            if (!array_key_exists($field,$record))
                $record[$field]='';

        $this->table[]=$record;
        return($record);
    }
    function checkfile()
    {
        $current=stat($this->path);
/*
        print("Current={$current['mtime']} {$current['size']} previous={$this->stat['mtime']} {$this->stat['size']}\n");
        print(filesize($this->path)." = ");
        print(file_get_contents($this->path));
*/
        if ($current['mtime']!=$this->stat['mtime'] ||
            $current['size']!=$this->stat['size'])
            $this->readfile();
    }

}

class dbcsv_daemon extends pfDaemonServer
{
    private $files;

    function __construct()
    {
        $files=array();
        parent::__construct('dbcsv');
    }
    function __destruct()
    {
        if ($this->files)
            foreach ($this->files as $file)
                if ($file->writewhen)
                    $file->writefile();
    }
    function _Process()
    {
        if ($this->files)
            foreach ($this->files as $file)
                $file->_Process();
    }
    function findfile($path)
    {
        if (!file_exists($path)) return(null);
        $stat=stat($path);
        $index=$stat['dev']."-".$stat['ino'];
        if (empty($this->files[$index]))
            $this->files[$index]=new dbcsv_file($path);
        $file=$this->files[$index];
        $file->checkfile();
        return($file);
    }
    function setkey($path,$key)
    {
        $file=$this->findfile($path);

        if (!in_array($key,$file->fields))
            return(new pfDaemonError("key '$key' is not in fields"));

        $file->key=$key;
        $file->readfile(); // force re-read to set keyhigh
    }
    function setfields($path,$fields)
    {
        // convert detailed field list to just names
        if (is_array(reset($fields)))
            $fields=array_keys($fields);

        // set/add fields
        $file=$this->findfile($path);
        if (!$file)
        {
            // create empty file
            $fp=fopen($path,"w");
            fputcsv($fp,$fields);
            fclose($fp);

            $file=$this->findfile($path);
        }

        foreach ($fields as $name)
        {
            if (!in_array($name,$file->fields))
                $file->fields[]=$name;
        }
        return(null);
    }
    function records($path,$where=false)
    {
        $file=$this->findfile($path);
        if ($where)
        {
            $matched=array();
            foreach ($file->table as $record)
            {
                if ($file->MatchWhere($record,$where))
                    $matched[]=$record;
            }
            return($matched);
        }
        return($file->table);
    }
    function fields($path)
    {
        $file=$this->findfile($path);
//        return(array_keys($file->table[0]));
        return($file->fields);
    }
    function insert($path,$record)
    {
        $file=$this->findfile($path);
        return($file->addrecord($record));
    }
    function lookup($path,$where)
    {
        $file=$this->findfile($path);
        foreach ($file->table as $record)
            if ($file->MatchWhere($record,$where))
                return($record);
        return(null);
    }
    function delete($path,$record)
    {
        $file=$this->findfile($path);

        $where=false;
        $match=array();
        foreach ($record as $key => $value)
        {
            if (!in_array($key,$file->fields))
            {
                // presume $record is actually a $where
                $where=$record;
                break;
            }
            // build where that matches record
            $match[]=array($key,$value);
        }
        if (!$where)
            $where=$match;

        $delete=array();
        foreach ($file->table as $index => $record)
        {
            if ($file->MatchWhere($record,$where))
                $delete[]=$index;
        }

        foreach ($delete as $index)
        {
            unset($file->table[$index]);
        }

        if (count($delete))
            $file->write();

        return(null);
    }

    function bogus()
    {
        return(new pfDaemonError("bogus error"));
    }
}

if (!empty($argv[1]) && $argv[1]=="-daemon") new dbcsv_daemon();
