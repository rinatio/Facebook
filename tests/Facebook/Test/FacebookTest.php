<?php

class FacebookTest extends PHPUnit_Framework_TestCase
{
    public function testCreateTestUser()
    {
        $app = new TestFb(array(
            'appId'  => FB_TEST_APP_ID,
            'secret' => FB_TEST_SECRET,
        ));
        $user = $app->createUser();
        $this->assertInstanceOf('rinatio\\Facebook\\Test\\User', $user);
    }
}

class TestFb extends \rinatio\Facebook\Test\Facebook
{
    public function __construct($config)
    {
        \BaseFacebook::__construct($config);
    }

    protected function setPersistentData($key, $value) {}
    protected function getPersistentData($key, $default = false) {
        return $default;
    }
    protected function clearPersistentData($key) {}
    protected function clearAllPersistentData() {}
}
