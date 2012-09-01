<?php
class dbbase extends event
{
/*
  public function keys();
  public function fields();
  public function escape($data);
  public function records($where=NULL,$limit=NULL);
  public function lookup($where);
  public function unary($where);
  public function update($record,$where=NULL);
  public function insert($record);
  public function delete($record=NULL,$where=NULL);
*/
	public function __construct()
	{
	}

	// return copy of record with just my fields
	protected function RecordOfFields($record)
	{
		$mine=array();
		foreach ($this->field_list as $field)
		{
			if (array_key_exists($field,$record))
				$mine[$field]=$record[$field];
		}
		return($mine);
	}

	protected function WhereToString($input,$db)
	{
		if (is_string($input))
			return($input);

		if (!is_array($input))
			err::Fatal('where not understood');
//print("WhereToString - ");
//print_r($input);
//print("\n");

		$keyfields=array();
		$table_for_field=array();
		if (!is_array($db))
			$db=array($db);

		foreach ($db as $dbeach)
		{
			if (!is_array($dbeach->key_fields))
				err::Fatal('key fields not defined');

			$keyfields=array_merge($keyfields,$dbeach->key_fields);

			foreach ($dbeach->field_list as $field)
				$table_for_field[$field]=$dbeach->table;
		}

		if (!count($keyfields))
			err::Fatal('no key fields found');

		/*
		if ($this->table)
			$prefix=$this->table.".";
		else
			$prefix='';
		*/

		// check for primary keys supplied
		$primary='';
		foreach ($keyfields as $key)
		{
			if (empty($input[$key]))
			{
				$primary=false;
				break;
			}

			if ($primary) $primary.=' AND ';
			$escaped=$db[0]->escape($input[$key]);
			$prefix='';
			if (!empty($table_for_field[$key]))
				$prefix=$table_for_field[$key].".";
			$primary.="$prefix$key='$escaped'";
		}
		if ($primary) return($primary);

//print_r($input);
		$where=NULL;
		if (!array_key_exists(0,$input)) {
			// array is key => value pairs, assume AND between each
			foreach ($input as $key => $value) {
			  $where.=$this->WhereToString(array($key,$value),$db)." AND ";
			}
//print("$where\n");
			return substr($where,0,-(strlen(" AND ")));
		}

		if (count($input)==1) return($input[0]);

		if (count($input)==2)
		{
			// presume an equals operation if not given
			$input[2]=$input[1];
			$input[1]='=';
			// UNLESS operating on arrays
			if (is_array($input[0]))
				$input[1]='AND';

		}
		if (count($input)!=3)
			err::Fatal('where array!=3 not understood');

		$prefix='';

		if (is_array($input[0]))
		{
			$where.='('.$this->WhereToString($input[0],$db).')';
		}
		else
		{
			if (!empty($table_for_field[$input[0]]))
				$prefix=$table_for_field[$input[0]].".";
			$where.=$prefix.$input[0];
		}

		// stop here if [2] is NULL
		if ($input[2]===NULL)
			return($where);

		$where.=' '.$input[1].' ';

		// right hand is always converted to a constant string?
		$rhs_quote=false;

		if ($input[1]=='=') $rhs_quote=true;
		if ($input[1]=='LIKE') $rhs_quote=true;

		if ($rhs_quote && is_array($input[2])) $this->Fatal("mixing {$input[1]} with ".print_r($input[2],true));


		if ($rhs_quote)
			$where.="'".$db[0]->escape($input[2])."'";
		else
			$where.='('.$this->WhereToString($input[2],$db).')';

//print("$where\n");
		return($where);
	}
}
?>
