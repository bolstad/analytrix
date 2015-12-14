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
        if (isset($_SESSION[$key])) {
            $data = $_SESSION[$key];
            return $data;
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}