<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2015-12-14
 * Time: 23:17
 */

require "vendor/autoload.php";

use Analytrix\ParserHeader;


$headerData = new Analytrix\ParserHeader();

$data = json_decode(file_get_contents('data.json'),1);

#print_r($data);

$parsed = $headerData->getColumns( $data );

print_r($parsed);


