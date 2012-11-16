<?php

require_once(dirname(dirname(__FILE__))."/poof.php");

/*
    Daemon (background server process with IPC) that
    handles CSV files as a database
*/

class dbcsv_file
{
    private $path;
    private $stat;
    public $table;
    private $last;

    public function __construct($path)
    {
        $this->path=$path;
        $this->readfile();
        return($this);
    }
    function readfile()
    {
        $this->last=time();
        $this->table=array();

        $this->stat=stat($this->path);
        $fp=fopen($this->path,"r");
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
        fclose($fp);
    }
    function checkfile()
    {
        $current=stat($this->path);
        if ($current['mtime']!=$this->stat['mtime'])
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
    function findfile($path)
    {
        $stat=stat($path);
        $index=$stat['dev']."-".$stat['ino'];
        if (empty($this->files[$index]))
            $this->files[$index]=new dbcsv_file($path);
        return($this->files[$index]);
    }
    function records($path,$where=false)
    {
        $file=$this->findfile($path);
        $file->checkfile();
        return($file->table);
    }
    function fields($path)
    {
        $file=$this->findfile($path);
        $file->checkfile();
        return(array_keys($file->table[0]));
    }

}

if (!empty($argv[1]) && $argv[1]=="-daemon") new dbcsv_daemon();
