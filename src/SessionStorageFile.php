<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2015-12-14
 * Time: 21:47
 */

namespace Analytrix;


class SessionStorageFile implements SessionStorageInterface
{
    private $fileName;
    private $data;
    private $directory = './';
    private $fullPath;

    function setBucket($bucketName)
    {
        // TODO: Implement setBucket() method.
        $this->fileName = '.'. md5($bucketName) .'.' .  preg_replace("/[^a-z0-9.]+/i", "", $bucketName) . ".storage";
        $this->fullPath = $this->directory . $this->fileName;
        echo "$this->fullPath\n";
        if (file_exists($this->fullPath)) {
            $this->data = json_decode(file_get_contents($this->fullPath),1);
        } else {
            $this->data = array();
        }
    }

    function get($key)
    {
        if (isset( $this->data[$key])) {
            return $this->data[$key];
        } else {
            return;
        }
        // TODO: Implement __get() method.
    }

    function set($key, $value)
    {
        // TODO: Implement __set() method.
        $this->data[$key] = $value;
        file_put_contents($this->fullPath,json_encode($this->data));

    }
}