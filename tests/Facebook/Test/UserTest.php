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
        $this->assertEquals($users[$user->id]->name, $user->name);
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

    public function testFetchProfile()
    {
        $user = User::create(array(
            'name' => 'Robin Hood'
        ));
        $user->fetchProfile();
        $this->assertEquals('Robin Hood', $user->name);
        $this->assertEquals('Robin', $user->first_name);
        $this->assertEquals('Hood', $user->last_name);
    }


    public function testAddUser()
    {
        $user1 = User::create();
        $user2 = User::create();
        $this->assertTrue($user1->addFriend($user2));
    }

    public function testUpdate()
    {
        $user = User::create(array(
            'name' => 'Robin Hood'
        ));
        $result = $user->update(array(
            'name' => 'Russell Crowe',
            'password' => 'new-password'
        ));
        $this->assertTrue($result);
        $this->assertEquals('Russell Crowe', $user->name);
        $this->assertEquals('new-password', $user->password);
        $user->fetchProfile();
        $this->assertEquals('Russell Crowe', $user->name);
        $this->assertEquals('new-password', $user->password);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Properties cannot be empty
     */
    public function testUpdateEmptyPropertiesExceptions()
    {
        User::create()->update(array());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot change email
     */
    public function testUpdateBadPropertiesExceptions()
    {
        User::create()->update(array(
            'email' => 'changed@example.com'
        ));
    }
}
