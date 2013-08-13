<?php

namespace rinatio\Facebook\Test;

use rinatio\Facebook\Facebook;

/**
 * Class User
 *
 * @link https://developers.facebook.com/docs/test_users/
 * @package rinatio\Facebook\Test
 */
class User
{
    public function __construct(array $data)
    {
        foreach ($data as $k=> $v) {
            $this->{$k} = $v;
        }
    }

    /**
     * Create new Facebook test user
     *
     * @return User
     */
    public static function create()
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        $path = '/' . Facebook::getAppId() . '/accounts/test-users';
        $response = $client->post($path, null, array(
            'access_token' => Facebook::getAppAccessToken()
        ))->send()->json();
        return new User($response);
    }

    /**
     * Get list of application test users.
     *
     * @return array
     */
    public static function all()
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        $request = $client->get('/' . Facebook::getAppId() . '/accounts/test-users');
        $request->getQuery()->set('access_token', Facebook::getAppAccessToken());
        $response = $request->send()->json();
        $users = array();
        foreach($response['data'] as $userData) {
            $user = new User($userData);
            $users[$user->id] = $user;
        }
        return $users;
    }

    /**
     * Delete test user
     *
     * @return bool true on success, false on failure
     */
    public function delete()
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        $success = $client->delete('/' . $this->id, null, array(
            'access_token' => Facebook::getAppAccessToken()
        ))->send()->json();
        return $success;
    }
}
