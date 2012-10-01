<?php
    require_once "poof.php";

    class SpanTest extends PHPUnit_Framework_TestCase
    {
        public function testSpan1()
        {
            $this->expectOutputString("
<div id=\"span1\" class=\"span1\">
<span id=\"html1\">Test1</span>
</div>");

            echo uiSpan(1)->Add("Test1");
        }
    }
