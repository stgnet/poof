<?php
	require "poof.php";

	class arFileTest extends PHPUnit_Framework_TestCase
	{
		public function testLoad()
		{
			$data=arFile("Tests/testdata.txt");

			$this->assertEquals(3,count($data));
		}
	}
?>
