<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2015-12-14
 * Time: 22:03
 */

namespace Analytrix;


class SessionStoragePhpSession implements SessionStorageInterface
{

    public function __construct()
    {
#        session_start();

    }

    public function setBucket($bucketName)
    {
        // TODO: Implement setBucket() method.

    }

    public function get($key)
    {
        // TODO: Implement __get() method.
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        else
        {
            return false;
        }
    }

    public function set($key, $value)
    {
        // TODO: Implement __set() method.
        $_SESSION[$key] = $value;
    }
}