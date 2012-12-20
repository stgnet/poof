<?php
/**
 * Wraps another db instance with a specific where class
 * Useful for editing a single record or set of matching records
 */
class dbWhere extends dbBase
{
    private $db;
    private $where;

    public function __construct($db,$where)
    {
        $this->db=$db;
        $this->where=$where;
    }

    public function keys()
    {
        return($this->db->keys());
    }
    public function fields()
    {
        return($this->db->fields());
    }
    public function escape($data)
    {
        Fatal("Not implemented");
    }
    public function records($where=NULL,$limit=NULL)
    {
        Fatal("records() is not valid use for dbWhere class");
    }
    public function lookup($where=null)
    {
        if ($where)
            $where=array($this->where,$where);
        else
            $where=$this->where;
        return($this->db->lookup($where));
    }
    public function unary($where=null)
    {
        if ($where)
            $where=array($this->where,$where);
        else
            $where=$this->where;
        return($this->db->unary($where));
    }
    public function update($record,$where=NULL)
    {
        if ($where)
            $where=array($this->where,$where);
        else
            $where=$this->where;
        return($this->db->update($record,$where));
    }
    public function insert($record)
    {
        foreach ($this->where as $field => $value)
            if (empty($record[$field]))
                $record[$field]=$value;
        return($this->db->insert($record));
    }
    public function delete($record=NULL)
    {
        foreach ($this->where as $field => $value)
            if (empty($record[$field]))
                $record[$field]=$value;
        return($this->db->delete($record));
    }
}
