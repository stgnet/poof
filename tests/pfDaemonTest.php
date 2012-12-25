<?php
    require_once "poof.php";

    class pfDaemonTest extends PHPUnit_Framework_TestCase
    {
        public function test_paths()
        {
            $daemon=pfDaemon("dbcsv"); //dbCsv("tests/test1.csv");

            $exception=false;
            $returned=false;

            try
            {
                $return=$daemon->test_fatal();
                $returned=true;
            }
            catch (Exception $e)
            {
                //print_r((string)$e);
                $exception=true;
            }

            $this->assertTrue($exception);
            $this->assertFalse($returned);
        }

    }
?>
