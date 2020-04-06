<?php

namespace Helpers;


class Cookie {
       /**
     * Set expiration time, default Config::EXPIRE
     */

    public static $expire = COOKIE_EXPIRE;

    /**
     * Restirct the path for cookie
     */

    public static $path = '/';

    /**
     * Restrict domain for cookies
     */

    public static $domain = NULL;

    /**
     * Define secure default true
     */

    public static $secure = false;

    /**
     * Define only http or not. if true - only http, if false both http or https
     */

    public static $onlyHttp = false;

    /**
     * Define salt for cookie
     * Default Config::keyword
     */

    public static $salt = '778fb1870187f2c7f5ed4f7cd20607fc';

    /**
     * @param $key
     * @return null
     * Method for deleting cookie
     */

    public static function delete($key)
    {
        if(isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            return self::_setcookie($key, NULL, -86400, Cookie::$path, Cookie::$domain, Cookie::$secure, Cookie::$onlyHttp);
        }

        return null;
    }


    /**
     * @param $key
     * @param null $default
     * @return null
     * Get cookie value
     */
    public static function get($key, $default = NULL)
    {
        if(!isset($_COOKIE[$key])) {
            return $default;
        }

        $cookie = $_COOKIE[$key];

        $split = strlen(self::salt($key, NULL));

        if (isset($cookie[$split]) AND $cookie[$split] == '~')
        {
            // Separate the salt and the value
            list ($hash, $value) = explode('~', $cookie, 2);

            if (self::salt($key, $value) == $hash)
            {
                // Cookie signature is valid
                return $value;
            }

            // The cookie signature is invalid, delete it
            static::delete($key);
        }

        return $default;
    }

    /**
     * @param $key
     * @return bool
     * Check if cookie exists
     */
    public static function has($key)
    {
        return (isset($_COOKIE[$key])) ? true : false;
    }
    /**
     * @param $name
     * @param $value
     * @return mixed
     * @throws \Exception
     * Set salt for cookie
     */
    public static function salt($name, $value)
    {
        // Require a valid salt
        if ( ! self::$salt)
        {
            throw new \Exception("Cookie salt is not defined. Please set cookie salt in configuration");
        }

        // Determine the user agent
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

        return hash_hmac('sha1', $agent.$name.$value.self::$salt, self::$salt);
    }

    /**
     * @param $name
     * @param $value
     * @param null $expire
     * @return mixed
     * @throws \Exception
     * Set cookie
     */
    public static function set($name, $value, $expire = NULL)
    {
        if ($expire == NULL)
        {
            // Use the default expiration
            $expire = self::$expire;
        }

        if ($expire != 0)
        {
            // The expiration is expected to be a UNIX timestamp
            $expire += static::_time();
        }

        // Add the salt to the cookie value
        $value = self::salt($name, $value).'~'.$value;

        return static::_setcookie($name, $value, $expire, self::$path, self::$domain, self::$secure, self::$onlyHttp);
    }

    /**
     * @param $name
     * @param $value
     * @param $expire
     * @param $path
     * @param $domain
     * @param $secure
     * @param $httponly
     * @return mixed
     * Main protected function for setting cookie
     */
    protected static function _setcookie($name, $value, $expire, $path, $domain, $secure, $httponly)
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * Get default time
     */


    protected static function _time()
    {
        return time();
    }

}