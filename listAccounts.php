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


$headerData = new Analytrix\ParserHeader();


function getVisits( $ding, $profile ) {

    global $headerData;

    echo "profil: $profile\n";

    $ding->ga->setAccountId( "ga:".$profile );

    // Set the default params. For example the start/end dates and max-results
    $defaults = array(
        'start-date' => date( 'Y-m-d', strtotime( '-1 month' ) ),
        'end-date' => date( 'Y-m-d' ),
    );
    $ding->ga->setDefaultQueryParams( $defaults );

    // Example1: Get visits by date
    $params = array(
        'metrics' => 'ga:visits',
        'dimensions' => 'ga:hostname',
    );
    $visits = $ding->ga->query( $params );

    $data = json_encode( $visits, 1);

    file_put_contents('data.json',$data);

    $parsed = $headerData->getColumns( $visits );

    print_r($parsed);
}

if ( $auth = $ding->storage->get('auth') ) {

    $accessToken = $auth['access_token'];
    $tokenExpires = $auth['expires_in'];

    $ding->ga->setAccessToken( $accessToken );
    $ding->ga->setAccountId( $ACCOUNT_ID );

    // Load profiles
    $profiles = $ding->ga->getProfiles();
#    print_r($profiles);
    $accounts = array();
    foreach ($profiles['items'] as $item) {
        $id = "ga:{$item['id']}";
        $name = $item['name'];
        $accounts[$id] = $name;
        echo "---\n $name\n";
        getVisits($ding, $item['id']);

    }

    #print_r($accounts);
die;

    try {
        $profiles = $ding->ga->management_profiles
            ->listManagementProfiles('123456', 'UA-123456-1');

    } catch (apiServiceException $e) {
        print 'There was an Analytics API service error '
            . $e->getCode() . ':' . $e->getMessage();

    } catch (apiException $e) {
        print 'There was a general API error '
            . $e->getCode() . ':' . $e->getMessage();
    }


}



