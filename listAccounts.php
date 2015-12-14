<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2015-12-14
 * Time: 22:53
 */

date_default_timezone_set( 'Europe/Stockholm' );
session_start();

include 'vendor/autoload.php';

$ding = new Analytrix\Basic(  new \Dotenv\Dotenv(__DIR__), new \timgws\GoogleAnalytics\API, new Analytrix\SessionStorageFile);

$ding->storage->setBucket('christian@carnaby.se');
$ACCOUNT_ID =  getenv( 'ACCOUNT_ID' );
echo "ACCOUNT_ID: $ACCOUNT_ID\n";

$ding->LoginText = 'Please login here';
$ding->DieOnNoSession = true;

$ding->run();

$auth = $ding->storage->get('auth');

if ( $auth = $ding->storage->get('auth') ) {

    $accessToken = $auth['access_token'];
    $tokenExpires = $auth['expires_in'];

    $ding->ga->setAccessToken( $accessToken );
    $ding->ga->setAccountId( $ACCOUNT_ID );

   
}



