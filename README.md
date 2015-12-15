## Analytrix

A PHP jump start kit for Google Analytics-based web apps. Setup your GA-API settings in your enviroments ettings (or a .env file) and you a
are ready to go. 

Rest on the shoulder of the gigants: __timgws/google-analytics-api__ and __vlucas/phpdotenv__

## Code Example

```php
date_default_timezone_set( 'Europe/Stockholm' );
session_start();

include 'vendor/autoload.php';

$ding = new Analytrix\Basic(  new \Dotenv\Dotenv(__DIR__), new \timgws\GoogleAnalytics\API, new Analytrix\SessionStorageFile);

$ding->storage->setBucket('bucket name');
$ACCOUNT_ID = 'ga:123456-1';
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


```

## Installation

* Install this library via composer: `composer require 'bolstad/analytrix:dev-master'`

* Create a Project in the Google APIs Console: https://code.google.com/apis/console/
* Enable the Analytics API under Services
* Under API Access: Create an Oauth 2.0 Client-ID
* Give a Product-Name, choose **Web Application** or **Service Account** depending on your needs
* Web Application: Set a redirect-uri in the project which points to your apps url

* Config your ENV with the aplication API vars with these names: CLIENT_ID, CLIENT_SECRET & REDIRECT_URI

## API Reference

Depending on the size of the project, if it is small and simple enough the reference docs can be added to the README. For medium size to larger projects it is important to at least provide a link to where the API reference docs live.

Describe and show how to run the tests with code examples.

## Contributors

Christian Bolstad <christian@bolstad.se> 

## License

MIT 

