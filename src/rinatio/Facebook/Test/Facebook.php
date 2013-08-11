<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rinat
 * Date: 11.08.13
 * Time: 23:15
 * To change this template use File | Settings | File Templates.
 */
namespace rinatio\Facebook\Test;

class Facebook extends \Facebook
{

    public function createUser()
    {
        $url = $this->getAppId() . '/accounts/test-users';
        $data = static::api($url, 'post');
        return new User($data);
    }
}
