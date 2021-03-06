<?php

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

    echo "Yes, session is set\n";

    print_r( $auth );

    $accessToken = $auth['access_token'];
    $tokenExpires = $auth['expires_in'];

    $ding->ga->setAccessToken( $accessToken );
    $ding->ga->setAccountId( $ACCOUNT_ID );

    // Set the default params. For example the start/end dates and max-results
    $defaults = array(
        'start-date' => date( 'Y-m-d', strtotime( '-1 month' ) ),
        'end-date' => date( 'Y-m-d' ),
    );
    $ding->ga->setDefaultQueryParams( $defaults );

    // Example1: Get visits by date
    $params = array(
        'metrics' => 'ga:visits',
        'dimensions' => 'ga:date',
    );
    $visits = $ding->ga->query( $params );

    echo '<pre>';
    print_r( $visits );
    echo '</pre>';
    die;
}



