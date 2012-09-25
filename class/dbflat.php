<?php
class dbFlat extends dbBase
{
	private $table;
	private $fields;

	private function ReadFlat($file)
	{
		$this->table=array();

		$fp=fopen($file,"r");
		$record=array();
		while ($line=fgets($fp))
		{
			$first=true;
			foreach ($this->fields as $name => $pattern)
			{
				$store=false;
				if (preg_match($pattern,rtrim($line),$match))
				{
					if ($first)
						$record=array();
					$record[$name]=$match[1];
					$store=true;
				}
				$first=false;
			}
			if ($store)
				$this->table[]=$record;
		}
		fclose($fp);
	}

	public function __construct($file,$fields)
	{
		$this->fields=$fields;
		$this->ReadFlat($file);
	}

	public function keys()
	{
		Fatal("Not implemented");
	}
	public function fields()
	{
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
		Fatal("This database is read-only");
	}
	public function insert($record)
	{
		Fatal("This database is read-only");
	}
	public function delete($record=NULL,$where=NULL)
	{
		Fatal("This database is read-only");
	}

}
