<?php
	require_once "poof.php";

	class arFileTest extends PHPUnit_Framework_TestCase
	{
		public function testLoad()
		{
			$data=arFile("tests/testdata.txt");

			$this->assertEquals(3,count($data));
		}
	}
?>
