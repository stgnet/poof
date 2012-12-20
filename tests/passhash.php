<?php
    require_once "poof.php";

    class passhash extends PHPUnit_Framework_TestCase
    {
        public function test_pass()
        {
            $hash=password_hash('secret-password',PASSWORD_DEFAULT);

            $this->assertNotEmpty($hash);

            $this->assertTrue(password_verify('secret-password',$hash));
            $this->assertFalse(password_verify('not-secret-password',$hash));
        }

    }
?>
