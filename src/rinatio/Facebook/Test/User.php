<?php

namespace rinatio\Facebook\Test;

use rinatio\Facebook\Facebook;
use Guzzle\Http\Client;

/**
 * Class User
 *
 * @link https://developers.facebook.com/docs/test_users/
 * @package rinatio\Facebook\Test
 * @property string $id
 * @property string $name
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $access_token
 */
class User
{
    /**
     * @var array Facebook create test user response data
     */
    protected $response = array();

    /**
     * @var array Facebook profile fields
     */
    protected $profile = array();

    /**
     * @param array $response
     */
    protected function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * @var null|\Guzzle\Http\Client
     */
    protected static $client;

    /**
     * Get a Facebook response or profile property
     *
     * @param string $name
     * @return mixed Facebook create test user response property or profile field
     */
    public function __get($name)
    {
        if(array_key_exists($name, $this->profile)) {
            return $this->profile[$name];
        }
        if(array_key_exists($name, $this->response)) {
            return $this->response[$name];
        }
    }

    /**
     * Get the Guzzle client for requests
     *
     * @return Client
     */
    protected static function getClient()
    {
        if(!static::$client) {
            static::$client = new Client('https://graph.facebook.com');
        }
        return static::$client;
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
     * Create new Facebook test user. Note that after create request
     * you have only few properties available
     * (an id, access_token, email, login_url and password at this point).
     * You should run {@link User::fetchProfile()} if you need others.
     *
     * @param array $parameters a parameters for create request like name,
     * locale, installed etc.
     * @return User
     */
    public static function create(array $parameters = array())
    {
        $path = '/' . Facebook::getAppId() . '/accounts/test-users';
        $response = static::getClient()->post($path, null, array_merge(array(
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
        $request = static::getClient()->get('/' . Facebook::getAppId() . '/accounts/test-users');
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
        return static::getClient()->delete('/' . $id, null, array(
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
        return static::getClient()->get('/' . $this->id)->send()->json();
    }

    /**
     * Fetch Facebook graph api user profile data
     *
     * @see requestUserProfile()
     */
    public function fetchProfile()
    {
        $this->profile = array_merge($this->profile, $this->requestUserProfile());
    }

    /**
     * Add a friend connection
     *
     * @link https://developers.facebook.com/docs/test_users/#makingfriendconnections
     * @param User $friend
     * @return bool true on success, false on failure
     */
    public function addFriend(User $friend)
    {
        $client = static::getClient();
        $response = $client->post($this->id . '/friends/' . $friend->id, null, array(
            'access_token' => $this->access_token
        ))->send()->json();
        if($response) {
            return $client->post($friend->id . '/friends/' . $this->id, null, array(
                'access_token' => $friend->access_token
            ))->send()->json();
        }
    }

    /**
     * Update user's name and/or password
     *
     * @link https://developers.facebook.com/docs/test_users/#changepw
     * @param array $properties an attributes to be changed. Only name and password
     * supported so far
     * @return bool
     * @throws \InvalidArgumentException if parameters are empty, or contain bad parameter
     */
    public function update(array $properties)
    {
        $changeable = array('name', 'password');
        $diff = array_diff_key($properties, array_flip($changeable));
        if(array() !== $diff) {
            $error = 'Cannot change ' . implode(', ', array_keys($diff));
            throw new \InvalidArgumentException($error);
        } elseif(empty($properties)) {
            throw new \InvalidArgumentException('Properties cannot be empty');
        }
        $success = static::getClient()->post($this->id, null, array_merge(array(
            'access_token' => Facebook::getAppAccessToken()
        ), $properties))->send()->json();
        if($success) {
            $this->profile = array_merge($properties, $this->profile);
            return true;
        }
    }
}
