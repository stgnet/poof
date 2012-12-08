<?php
class dbArray extends dbBase
{
    private $table;

    public function __construct($table)
    {
        $this->table=$table;
    }

    public function keys()
    {
        return(array());
    }
    public function fields()
    {
        if (empty($this->table[0]))
            return(array());
        return(array_keys($this->table[0]));
    }
    public function escape($data)
    {
        Fatal("Not implemented");
    }
    public function records($where=NULL,$limit=NULL)
    {
        return($this->table);
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
    public function delete($record=NULL)
    {
        Fatal("Not implemented");
    }
}
