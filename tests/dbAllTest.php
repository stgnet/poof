<?php
    require_once "poof.php";

    class dbAllTest extends PHPUnit_Framework_TestCase
    {
        // make sure that all database types implement base functionality

        public function test_fields()
        {
            foreach(array('dbcsv'=>"tests/dbtest1.csv") as $type => $arg)
            {
                $db=$type($arg);

                $fields=array(
                    'key'=>     array('type'=>"hidden",'desc'=>"key value"),
                    'fname'=>   array('type'=>"text",'desc'=>"First Name"),
                    'lname'=>   array('type'=>"text",'desc'=>"Last Name")
                );

                // set the fields

                $db->SetFields($fields);

                // get the field list back
                $fieldlist=$db->Fields();

                // did it get translated correctly?
                $this->assertCount(3,$fieldlist);
                $this->assertArrayHasKey(0,$fieldlist);
                $this->assertEquals('key',$fieldlist[0]);
                $this->assertArrayHasKey(1,$fieldlist);
                $this->assertEquals('fname',$fieldlist[1]);
                $this->assertArrayHasKey(2,$fieldlist);
                $this->assertEquals('lname',$fieldlist[2]);
            }
        }
    }
?>
