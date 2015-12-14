<?php

namespace Analytrix;


interface SessionStorageInterface
{
    public function setBucket( $bucketName );
    public function get( $key );
    public function set( $key, $value );

}