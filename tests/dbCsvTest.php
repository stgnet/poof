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
            if (file_exists($file)) unlink($file);

            // change contents on disk, ask for records again
            file_put_contents($file,"alpha,beta\n1,2\n3,4\n");
            sleep(20);
            $this->assertEquals(2,count($test4->records()));
        }
        */

        public function test_creation()
        {
            $file="tests/test5.csv";
            if (file_exists($file)) unlink($file);

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

            $records=$test5->records();

            $this->assertEquals(1,count($records));

            sleep(4);
            unlink($file);
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
            if (file_exists($file)) unlink($file);

            $fields=array('key','alpha','beta');

            $test7=dbcsv($file)->SetFields($fields);

            $record=array('key'=>1,'alpha'=>"abc");
            $test7->insert($record);

            $records=$test7->records();

            $this->assertEquals(1,count($records));
            $this->assertEquals('1',$records[0]['key']);
            $this->assertEquals('abc',$records[0]['alpha']);

            $this->assertArrayHasKey('beta',$records[0]);
            $this->assertEquals('',$records[0]['beta']);

            sleep(4);
            unlink($file);
        }
        function test_key()
        {
            $file="tests/test8.csv";
            if (file_exists($file)) unlink($file);

            $fields=array('key','alpha','beta');

            $test8=dbcsv($file)->SetFields($fields,'key');

            $test8->insert(array('alpha'=>"abc",'beta'=>"def"));
            $test8->insert(array('alpha'=>"ghi",'beta'=>"jkl"));
            $test8->insert(array('alpha'=>"nmo",'beta'=>"pqrs"));
            $test8->insert(array('alpha'=>"tuv",'beta'=>"wxyz"));

            $records=$test8->records();

            $this->assertEquals(4,count($records));

            sleep(4);
            $this->assertEquals("key,alpha,beta
1,abc,def
2,ghi,jkl
3,nmo,pqrs
4,tuv,wxyz
",file_get_contents($file));

            unlink($file);
        }
        function test_update()
        {
            $file="tests/test9.csv";
            if (file_exists($file)) unlink($file);

            $fields=array('key','alpha','beta');

            $test9=dbcsv($file)->SetFields($fields,'key');

            $test9->insert(array('alpha'=>"abc",'beta'=>"def"));
            $test9->insert(array('alpha'=>"ghi",'beta'=>"jkl"));
            $test9->insert(array('alpha'=>"nmo",'beta'=>"pqrs"));
            $test9->insert(array('alpha'=>"tuv",'beta'=>"wxyz"));

            $record=$test9->lookup(array('key','3'));

            $this->assertArrayHasKey('alpha',$record);
            $this->assertEquals('nmo',$record['alpha']);

            $record=$test9->lookup(array('key'=>'3'));

            $this->assertArrayHasKey('alpha',$record);
            $this->assertEquals('nmo',$record['alpha']);

            // now change value

            $record['alpha']='octothorpe';
            $test9->update($record);

            // look it up again and confirm
            $record=$test9->lookup(array('key'=>'3'));

            $this->assertEquals('octothorpe',$record['alpha']);

            sleep(4);

            $this->assertEquals("key,alpha,beta
1,abc,def
2,ghi,jkl
3,octothorpe,pqrs
4,tuv,wxyz
",file_get_contents($file));

            sleep(4);
            unlink($file);
        }
        function test_create_unspecified_key()
        {
            $file="tests/testa.csv";
            if (file_exists($file)) unlink($file);

            $fields=array('alpha','beta');

            $testa=dbcsv($file)->SetFields($fields,'key');

            $testa->Insert(array('alpha'=>'one','beta'=>'two'));

            sleep(4);

            $this->assertEquals("key,alpha,beta
1,one,two
",file_get_contents($file));

            sleep(4);
            unlink($file);
        }
        function test_create_retrieve_key()
        {
            $file="tests/testb.csv";
            if (file_exists($file)) unlink($file);

            $fields=array(
                'alpha'=>array('type'=>"text",'desc'=>"Alpha"),
                'beta'=>array('type'=>"text",'desc'=>"Beta")
            );

            $testb=dbcsv($file)->SetFields($fields,'alpha');

            $fields_names=$testb->fields();

            $fields_detail=$testb->fields(true);

            $this->assertEquals(2,count($fields_names));
            $this->assertEquals($fields_names,array('alpha','beta'));

            $this->assertEquals(2,count($fields_detail));
            $this->assertEquals($fields,$fields_detail);

            $where=array('alpha'=>1);

            $whereb=dbWhere($testb,$where);

            $fields_names=$whereb->fields();

            $fields_detail=$whereb->fields(true);

            $this->assertEquals(2,count($fields_names));
            $this->assertEquals($fields_names,array('alpha','beta'));

            $this->assertEquals(2,count($fields_detail));
            $this->assertEquals($fields,$fields_detail);

            sleep(4);
            unlink($file);
        }
    }
?>
