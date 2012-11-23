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
        /* this test fails
        public function test_read_after_change()
        {
            $file="tests/test4.csv";
            file_put_contents($file,"alpha,beta\n1,2\n");
            $test4=dbCsv($file);
            $this->assertEquals(1,count($test4->records()));

            sleep(20);
            unlink($file);

            // change contents on disk, ask for records again
            file_put_contents($file,"alpha,beta\n1,2\n3,4\n");
            sleep(20);
            $this->assertEquals(2,count($test4->records()));
        }
        */

        public function test_creation()
        {
            $file="tests/test5.csv";
            unlink($file);

            $fields=array(
                'username'=>array('type'=>"text",'desc'=>"Email"),
                'password'=>array('type'=>"password",'desc'=>"Password"),
                'fname'=>array('type'=>"text",'desc'=>"First Name"),
                'lname'=>array('type'=>"text",'desc'=>"Last Name")
            );

            $test5=dbcsv($file)->SetFields($fields);

            $this->assertEquals(0,count($test5->records()));

            $record=array(
                'username'=>"test@user.com",
                'password'=>"secret",
                'fname'=>"John",
                'lname'=>"Doe"
            );

            $test5->insert($record);

            $this->assertEquals(1,count($test5->records()));
        }
        function test_lookup_delete()
        {
            $file="tests/test6.csv";
            file_put_contents($file,"key,alpha,beta
1,one,two
2,two,two
3,three,four
4,four,five
");
            $test6=dbcsv($file);

            $record=$test6->lookup(array('key',3));

            $this->assertEquals(3,count($record));
            $this->assertArrayHasKey('key',$record);
            //fwrite(STDOUT,"RECORD=".print_r($record,true));
            $this->assertEquals(3,$record['key']);
            $this->assertEquals("three",$record['alpha']);
            $this->assertEquals("four",$record['beta']);

            $records=$test6->records(array('beta',"two"));

            $this->assertEquals(2,count($records));
            $this->assertEquals(1,$records[0]['key']);
            $this->assertEquals(2,$records[1]['key']);

            $records=$test6->records(array('key',"none"));

            $this->assertEquals(0,count($records));

            $record=$test6->lookup(array('key',2));

            $this->assertEquals(2,$record['key']);
            $test6->delete($record);

            $this->assertEquals(3,count($test6->records()));

        }
        function test_create()
        {
            $file="tests/test7.csv";
            unlink($file);

            $fields=array('key','alpha','beta');

            $test7=dbcsv($file);

            $test7->SetFields($fields);

            $record=array('key'=>1,'alpha'=>"abc");
            $test7->insert($record);

            $records=$test7->records();

            //print_r($records);
            $this->assertEquals(1,count($records));
            $this->assertEquals('1',$records[0]['key']);
            $this->assertEquals('abc',$records[0]['alpha']);
            $this->assertArrayHasKey('beta',$records[0]);
            $this->assertEquals('',$records[0]['beta']);
        }
        function test_key()
        {
            $file="tests/test8.csv";
            unlink($file);

            $fields=array('key','alpha','beta');

            $test8=dbcsv($file)->SetFields($fields)->SetKey('key');

            $test8->insert(array('alpha'=>"abc",'beta'=>"def"));
            $test8->insert(array('alpha'=>"ghi",'beta'=>"jkl"));
            $test8->insert(array('alpha'=>"nmo",'beta'=>"pqrs"));
            $test8->insert(array('alpha'=>"tuv",'beta'=>"wxyz"));

            $records=$test8->records();

            $this->assertEquals(4,count($records));

            $this->assertEquals("key,alpha,beta
1,abc,def
2,ghi,jkl
3,nmo,pqrs
4,tuv,wxyz
",file_get_contents($file));


        }
    }
?>
