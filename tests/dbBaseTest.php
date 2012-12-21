<?php
    require_once "poof.php";

    class dbBaseTest extends PHPUnit_Framework_TestCase
    {
        public function test_all()
        {
            $db=dbBase();

            $record=array(
                'alpha'=>"one",
                'beta'=>"two"
            );

            $this->assertEquals(true,$db->MatchWhere($record,array('alpha',"one")));
            $this->assertEquals(true,$db->MatchWhere($record,array('beta',"two")));
            $this->assertEquals(true,$db->MatchWhere($record,array(
                array('alpha',"one"),
                array('beta',"two")
            )));

            $this->assertEquals(false,$db->MatchWhere($record,array('alpha',"zero")));
            $this->assertEquals(false,$db->MatchWhere($record,array('beta',"one")));

            //$this->assertEquals(false,$db->MatchWhere($record,"bogus not array"));
            //$this->assertEquals(false,$db->MatchWhere($record,array('noexist',"one")));
        }
    }
?>
