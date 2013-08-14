<?php

use rinatio\Facebook\Test\User;
use rinatio\Facebook\Facebook;


class UserTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        Facebook::setAppId(FB_TEST_APP_ID);
        Facebook::setAppSecret(FB_TEST_APP_SECRET);
    }

    public function testCreate()
    {
        $user = User::create(array(
            'name' => 'John'
        ));
        $this->assertInstanceOf('rinatio\\Facebook\\Test\\User', $user);
        $this->assertNotEmpty($user->id);
    }

    public function testAll()
    {
        $user = User::create();
        $users = User::all();
        $this->assertInternalType('array', $users);
        $this->assertArrayHasKey($user->id, $users);
    }

    public function testDelete()
    {
        $user = User::create();
        $this->assertTrue($user->delete());
    }

    public function testDeleteAll()
    {
        User::create();
        User::create();
        User::deleteAll();
        $users = User::all();
        $this->assertInternalType('array', $users);
        $this->assertEmpty($users);
    }
}
