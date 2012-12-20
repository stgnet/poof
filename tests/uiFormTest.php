<?php
	require_once "poof.php";

	class uiFormTest extends PHPUnit_Framework_TestCase
	{
		public function test_inline()
		{
            $fields=array('test'=>array('type'=>"text",'desc'=>"Test"));
            $record=array('test'=>"value");

            $form=uiForm($fields,$record,"inline");

            $html=(string)$form;
            //fwrite(STDOUT,"START:".$html.":END\n");

            $proper='
<form id="form1" class="form-inline">
<input id="input_text1" name="test" type="text" value="value" placeholder="Test" />
</form>';
            $this->assertEquals($proper,$html);
		}
	}
?>
