<?php
    require_once "poof.php";

    class dbCsvTest extends PHPUnit_Framework_TestCase
    {
        public function test_paths()
        {
            chdir("tests");
            $test2=dbCsv("test2.csv");
            $this->assertEquals(5,count($test2->fields()));
            chdir("..");
            $test3=dbCsv("tests/test3.csv");
            $this->assertEquals(5,count($test3->fields()));
        }

        public function test_read_only()
        {
            $test1=dbCsv(getcwd()."/tests/test1.csv");

            $fields=$test1->fields();

            $this->assertEquals(5,count($fields));
            $this->assertArrayHasKey(0,$fields);
            $this->assertEquals('Number',$fields[0]);
            $this->assertArrayHasKey(1,$fields);
            $this->assertEquals('First',$fields[1]);
            $this->assertArrayHasKey(2,$fields);
            $this->assertEquals('Last',$fields[2]);
            $this->assertArrayHasKey(3,$fields);
            $this->assertEquals('Username',$fields[3]);
            $this->assertArrayHasKey(4,$fields);
            $this->assertEquals('Check',$fields[4]);

            $records=$test1->records();

            $this->assertEquals(3,count($records));
            foreach ($records as $r)
                $this->assertEquals($r['Check'],
                 "{$r['Number']}-{$r['First']}-{$r['Last']}-{$r['Username']}");
        }
    }
?>
