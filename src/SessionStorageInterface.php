<?php

namespace Analytrix;


interface SessionStorageInterface
{
    public function setBucket( $bucketName );
    public function __get( $key );
    public function __set( $key, $value );

}