<?php 

date_default_timezone_set( 'Europe/Stockholm' );
session_start();

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Analytrix\Basic;

$test = new Analytrix\Basic(__DIR__);
