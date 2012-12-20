<?php
    require_once "poof.php";

    class pfDaemonTest extends PHPUnit_Framework_TestCase
    {
        public function test_paths()
        {
            $daemon=pfDaemon("dbcsv"); //dbCsv("tests/test1.csv");

            $exception=false;

            try
            {
                $return=$daemon->test_fatal();
                print_r($return);
            }
            catch (Exception $e)
            {
                print_r((string)$e);
                $exception=true;
            }

            $this->assertTrue($exception);
        }

    }
?>
