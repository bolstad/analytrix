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

#    echo "profil: $profile\n";

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

    if ($parsed = $headerData->getColumns( $visits ) ) {
        print_r($parsed);

        foreach($parsed as $entry) {
            $hostname = $entry['ga:hostname'];
            echo "$hostname\n";
            file_put_contents("hostnames.txt", $hostname . "\n", FILE_APPEND);

        }
    }

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
    $oldAccountId = '';
    $ccounter = 0;
    foreach ($profiles['items'] as $item) {
        #print_r($item);

        $accountId = $item['accountId'];
        if ($accountId != $oldAccountId)
        {
            echo "new: $accountId '$oldAccountId'\n";
            #die;
            file_put_contents("hostnames.txt", $hostname . "\n", FILE_APPEND);
            $oldAccountId = $accountId;
            $ccounter++;
        }
        echo "old: $oldAccountId new $accountId $ccounter\n";
        $id = "ga:{$item['id']}";
        $name = $item['name'];
        $accounts[$id] = $name;
     #   echo "---\n $name\n";
        getVisits($ding, $item['id']);

    }

    #print_r($accounts);
die;



}



