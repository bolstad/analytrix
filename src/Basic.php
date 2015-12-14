<?php 


namespace Analytrix;

use timgws\GoogleAnalytics\API as Analytics;


class Basic {

    var $dotenv;
    var $ga;

    function __construct( $configDir )
    {

        if (!isset($configDir)) {
            throw new \Exception('You need to set $configdir');
        }

        if (!is_dir($configDir)) {
            throw new \Exception("The directory defined in configDir does not exist '$configDir'");
        }

        // Setup Dotenv 
        $this->dotenv = new \Dotenv\Dotenv($configDir);
        $this->dotenv->load();
        $this->dotenv->required(array('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI'));
    }

    function run() {
        // Setup GA 
        $this->ga = new Analytics();
        $this->ga->auth->setClientId( getenv( 'CLIENT_ID' ) );  
        $this->ga->auth->setClientSecret( getenv( 'CLIENT_SECRET' ) );  
        $this->ga->auth->setRedirectUri( getenv( 'REDIRECT_URI' ) );  


        // Try to get the AccessToken
        if ( isset( $_GET['code'] ) ) {
            $code = $_GET['code'];
            $auth = $this->ga->auth->getAccessToken( $code );

            if ( $auth['http_code'] == 200 ) {
                $accessToken = $auth['access_token'];
                $refreshToken = $auth['refresh_token'];
                $tokenExpires = $auth['expires_in'];
                $tokenCreated = time();
                $_SESSION['auth'] = $auth;
            }
        }

        if ( !isset( $_SESSION['auth'] ) ) {
            $url = $this->ga->auth->buildAuthUrl();
            echo '<a href="'. $url . '">login here</a>';
            die;
        }
    }
}
