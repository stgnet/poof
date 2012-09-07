<?php
class dbcsv extends dbbase
{
	private $table;

	private function ReadCsv($file)
	{
		$this->table=array();

		$fp=fopen($file,"r");
		$header=fgetcsv($fp);
		while ($row=fgetcsv($fp))
		{
			$record=array();
			$index=0;
			foreach ($row as $data)
			{
				if (empty($header[$index]))
					$header[$index]="COL$index";
				$record[$header[$index]]=$data;
				$index++;
			}
			$this->table[]=$record;
		}
	}

	public function __construct($file)
	{
		$this->ReadCsv($file);
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
