<?php
class dbIni extends dbBase
{
    private $path;
    private $fields;
    private $key;
    private $fp;

    public function __construct($file)
    {
        $this->path=$file;
        $this->fields=false;
        $this->key='section';
        $this->fp=false;
    }
    private function readfile()
    {
        if (!$this->fp)
        {
            if (file_exists($this->path))
                $this->fp=fopen($this->path,"r+");
            else
                $this->fp=fopen($this->path,"x+");
        }

        /*
        if (!flock($this->fp,LOCK_SH))
            Fatal("unable to lock {$this->path}");
            */

        if (!$this->fields)
            $this->fields=array($this->key=>array('type'=>"text"));

        rewind($this->fp);
        $table=array();
        $key='unknown';
        while ($line=fgets($this->fp))
        {
            $exp=explode(';',trim($line),2);
            $line=trim($exp[0]);

            if ($line=="") continue;

            if ($line[0]=='[')
            {
                if (!preg_match('_\[(.*)\]_',$line,$match) || empty($match[1]))
                    Fatal("dbIni: key syntax error '$line'");
                $key=trim($match[1]);
                continue;
            }
            if (preg_match('_(.*)=[ ]*"(.*)"_',$line,$match) ||
                preg_match('_(.*)=(.*)_',$line,$match))
            {
                if (empty($match[1]) || !isset($match[2]))
                    Fatal("dbIni: value syntax error '$line'");

                if (empty($table[$key]))
                    $table[$key]=array($this->key=>$key);

                $name=trim($match[1]);
                $value=trim($match[2]);
                $table[$key][$name]=$value;

                if (empty($this->fields[$name]))
                    $this->fields[$name]=array($name=>array('type'=>"text"));

                continue;
            }
            Fatal("dbIni: unknown syntax error '$line'");
        }
        return($table);
    }
    private function writefile($table)
    {
        if (!$this->fp)
            Fatal("file is not open");

        /*
        if (!flock($this->fp,LOCK_EX))
            Fatal("unable to exclusively lock {$this->path}");
            */

        rewind($this->fp);
        ftruncate($this->fp,0);
        $date=date('Y/m/d H:i:s');
        //fwrite($this->fp,"; written by poof/class/dbIni timestamp=$date\n");
        foreach ($table as $key => $pairs)
        {
            fwrite($this->fp,"[$key]\n");

            foreach ($pairs as $name => $value)
            {
                if ($name==$this->key) continue;

                fwrite($this->fp,"$name = \"$value\"\n");
            }
        }
        fflush($this->fp);
        //flock($this->fp,LOCK_UN);

    }

    public function SetFields($fields,$key=false)
    {
        if ($key)
            $this->key=$key;
        $this->fields=$fields;
        return($this);
    }
    public function keys()
    {
        return(array($this->key));
    }
    public function fields($detailed=false)
    {
        if (!$this->fields)
            $this->readfile();

        if ($detailed)
            return($this->fields);
        else
            return(array_keys($this->fields));
    }
    public function escape($data)
    {
        Fatal("Not implemented");
    }
    public function records($where=NULL,$limit=NULL)
    {
        $table=$this->readfile();
        $records=array();
        foreach (safearray($table) as $record)
            if ($this->MatchWhere($record,$where))
                $records[]=$record;
        return($records);
    }
    public function lookup($where)
    {
        $table=$this->readfile();
        foreach ($table as $record)
            if ($this->MatchWhere($record,$where))
                return($record);
        return(null);
    }
    public function unary($where)
    {
        $record=$this->lookup($where);
        if ($record) return($record);
        Fatal("record not found");
    }
    public function update($update,$where=NULL)
    {
        $table=$this->readfile();

        if (empty($update[$this->key]))
            Fatal("key field '{$this->key}' must be provided");

        $key=$update[$this->key];

        /*
        $matched=false;
        foreach ($table as $index => $record)
        {
        print_r($record);
        print_r($update);
            if ($this->MatchWhere($record,$update))
            {
                $matched=true;
                foreach ($update as $name => $value)
                    $record[$name]=$value;

                $table[$index]=$record;
                break;
            }
        }
        if (!$matched)
            $table[]=$update;
            */
        if (empty($table[$key]))
            Fatal("key '$key' not found for update");

        foreach ($update as $name => $value)
            $table[$key][$name]=$value;

        $this->writefile($table);
    }
    public function insert($record)
    {
        $table=$this->readfile();

        if (empty($record[$this->key]))
            Fatal("key field '{$this->key}' must be provided");

        $table[$record[$this->key]]=$record;

        $this->writefile($table);
    }
    public function delete($delete=NULL)
    {
        $table=$this->readfile();

        $matched=false;
        foreach ($table as $index => $record)
        {
            if ($this->MatchWhere($record,$delete))
            {
                $matched=$index;
                break;
            }
        }
        if ($matched)
        {
            unset($table[$matched]);
            $this->writefile($table);
        }
    }

}
