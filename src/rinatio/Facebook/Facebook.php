<?php

namespace rinatio\Facebook;

class Facebook
{
    protected static $appId;
    protected static $secret;

    public static function setAppId($id)
    {
        static::$appId = $id;
    }

    public static function getAppId()
    {
        if(!isset(static::$appId)) {
            throw new \LogicException("App ID isn't set");
        }
        return static::$appId;
    }

    public static function setAppSecret($secret)
    {
        static::$secret = $secret;
    }

    public static function getAppSecret()
    {
        if(!isset(static::$secret)) {
            throw new \LogicException("App secret isn't set");
        }
        return static::$secret;
    }

    public static function getAppAccessToken() {
        return static::getAppId().'|'.static::getAppSecret();
    }
}
