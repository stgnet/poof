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
    public $detailed; // array of fields with full details
    public $writewhen; // when non-zero, when to write changed file
    public $key; // key field (unique id)
    public $keyhigh; // highest value of key field

    public function __construct($path)
    {
        $this->writewhen=false;
        $this->key=false;
        $this->keyhigh=false;
        $this->fields=array();
        $this->header=array();
        $this->detailed=array();
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
//$record['path']=$this->path;
            $this->table[]=$record;

            if ($this->key && !empty($record[$this->key]))
                if ($this->keyhigh<$record[$this->key])
                    $this->keyhigh=$record[$this->key];
        }

        foreach ($header as $name)
        {
            if (!in_array($name,$this->fields))
            {
                $this->fields[]=$name;
                $this->detailed[$name]=array('type'=>'text');
            }
        }

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
        $this->stat=stat($this->path);
        $this->writewhen=false;
    }
    function write()
    {
        // set write flag if not already
        if (!$this->writewhen)
            $this->writewhen=time()+1; // update file every 3 secs when changing
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

        // insure record added has all current fields
        foreach ($this->header as $field)
            if (!array_key_exists($field,$record))
                $record[$field]='';

//$record['path-add']=$this->path;

// temp hack: use delayed full write only
$this->table[]=$record;
$this->write();
return($this->table);

        // is there a column in record that is not in last read header?
        foreach ($record as $field => $value)
        {
            if (!in_array($field,$this->header))
            {
                // can't add single record, must rewrite entire file
                $this->table[]=$record;
                $this->write();
                return($this->table);
            }
        }

        $ordered=array();
        foreach ($this->header as $field)
                $ordered[]=(array_key_exists($field,$record)?$record[$field]:'');

        $fp=fopen($this->path,"a");
        fputcsv($fp,$ordered);
        fclose($fp);


        $this->table[]=$record;
        return($record);
    }
    function checkfile()
    {
        try
        {
            $current=stat($this->path);
        }
        catch (Exception $e)
        {
            // file has been deleted
            siDiscern('warning',"stat failed on {$this->path} indicating deleted file: ".$e->getMessage());
            $this->stat=array('mtime'=>0);
            $this->table=array();
            return;
        }
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
        $index=realpath($path); //$stat['dev']."-".$stat['ino'];
        if (empty($this->files[$index]))
            $this->files[$index]=new dbcsv_file($path);
        $file=$this->files[$index];
        $file->checkfile();
        return($file);
    }
    function keys($path)
    {
        $file=$this->findfile($path);
        if (empty($file->key))
        {
            // if no key has been supplied, presume first field is key
            $file->key=reset($file->fields);
            if (empty($file->key))
                return(false);
        }
        return(array($file->key));
    }
    function SetFields($path,$fields,$key)
    {
        $detailed=$fields;
        // convert detailed field list to just names
        if (is_array(reset($fields)))
            $fields=array_keys($fields);
        else
            foreach ($fields as $name)
                $detailed[$name]=array('type'=>"text",'desc'=>ucwords($name));

        if ($key && !in_array($key,$fields))
            array_unshift($fields,$key);
            //$fields[]=$key;
            //return(new pfDaemonError("key '$key' is not in fields"));

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
        $file->detailed=$detailed;
        $file->key=$key;

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
    function fields($path,$detailed=false)
    {
        $file=$this->findfile($path);

        if ($detailed)
            return($file->detailed);

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
    function delete($path,$where)
    {
        $file=$this->findfile($path);

        $delete=array();
        foreach ($file->table as $index => $record)
        {
            if ($file->MatchWhere($record,$where))
                $delete[]=$index;
        }

        foreach ($delete as $index)
            unset($file->table[$index]);

        if (count($delete))
            $file->write();

        return(null);
    }
    function update($path,$updated)
    {
        $file=$this->findfile($path);

        if (!$file->key)
            return(new pfDaemonError("no key field set for $path"));

        if (!array_key_exists($file->key,$updated))
            return(new pfDaemonError("no key field in record to update $path"));

        $where=array($file->key,$updated[$file->key]);

        foreach ($file->table as $index => $record)
        {
            if ($file->MatchWhere($record,$where))
                $file->table[$index]=$updated;
        }
        $file->write();
        return(null);
    }

    function DeleteFile($path)
    {
    }

    function test_fatal()
    {
        //return(new pfDaemonError("bogus error"));
        Fatal("this is a simulation of a fatal error");
    }
}

if (!empty($argv[1]) && $argv[1]=="-daemon") new dbcsv_daemon();
