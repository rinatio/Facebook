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
    /**
     * @var array Facebook create test user response data
     */
    protected $response = [];

    /**
     * @var array Facebook profile fields
     */
    protected $profile = [];

    /**
     * @param array $response
     */
    protected function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Get a Facebook response or profile property
     *
     * @param string $name
     * @return mixed Facebook create test user response property or profile field
     */
    public function __get($name)
    {
        if(array_key_exists($name, $this->response)) {
            return $this->response[$name];
        }
        if(array_key_exists($name, $this->profile)) {
            return $this->profile[$name];
        }
    }

    /**
     * Get Facebook response
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get user's profile
     *
     * @return array
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Create new Facebook test user
     *
     * @param array $parameters
     * @return User
     */
    public static function create(array $parameters = array())
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        $path = '/' . Facebook::getAppId() . '/accounts/test-users';
        $response = $client->post($path, null, array_merge(array(
            'access_token' => Facebook::getAppAccessToken()
        ), $parameters))->send()->json();
        return new static($response);
    }

    /**
     * Delete test user
     *
     * @return bool true on success, false on failure
     */
    public function delete()
    {
        return static::requestDelete($this->id);
    }

    /**
     * Get list of application test users
     *
     * @return array
     */
    public static function all()
    {
        $response = static::requestUserList();
        $users = array();
        foreach($response['data'] as $userData) {
            $user = new static($userData);
            $users[$user->id] = $user;
        }
        return $users;
    }

    /**
     * Delete all application test users
     */
    public static function deleteAll()
    {
        $response = static::requestUserList();
        foreach($response['data'] as $userData) {
            static::requestDelete($userData['id']);
        }
    }

    /**
     * Send request to get test user list
     *
     * @return array
     */
    protected static function requestUserList()
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        $request = $client->get('/' . Facebook::getAppId() . '/accounts/test-users');
        $request->getQuery()->set('access_token', Facebook::getAppAccessToken());
        return $request->send()->json();
    }

    /**
     * Send request to delete test user by ID
     *
     * @param $id
     * @return bool true on success, false on failure
     */
    protected static function requestDelete($id)
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        return $client->delete('/' . $id, null, array(
            'access_token' => Facebook::getAppAccessToken()
        ))->send()->json();
    }

    /**
     * Request Facebook graph api user profile data
     *
     * @link https://developers.facebook.com/docs/reference/api/user/
     * @return array
     */
    protected function requestUserProfile()
    {
        $client = new \Guzzle\Http\Client('https://graph.facebook.com');
        return $client->get('/' . $this->id)->send()->json();
    }

    /**
     * Fetch Facebook graph api user profile data
     *
     * @see requestUserProfile()
     */
    public function fetchProfile()
    {
        $this->profile = $this->requestUserProfile();
    }
}
