<?php

use rinatio\Facebook\Facebook;

class FacebookTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException LogicException
     */
    public function testGetAppIdException()
    {
        Facebook::getAppId();
    }

    /**
     * @expectedException LogicException
     */
    public function testGetAppSecretException()
    {
        Facebook::getAppSecret();
    }
}
