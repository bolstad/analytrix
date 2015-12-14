<?php 


namespace Analytrix;

use timgws\GoogleAnalytics\API as Analytics;


class Basic {

    var $dotenv;
    var $ga;

    private $data = array();

    function __construct( $dotenv = \Dotenv\Dotenv )
    {
        $this->dotenv = $dotenv;
        $this->dotenv->load();
        $this->dotenv->required(array('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI'));

        $this->data['LoginText'] = 'login here';
        $this->data['DieOnNoSession'] = true;
        $this->data['DebugMode'] = false;
    }


    public function __set($name, $value)
    {
        if ( $this->data['DebugMode']) {
            echo "Setting '$name' to '$value'\n";
        }
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if ( $this->data['DebugMode']) {
            echo "Getting '$name'\n";
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
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
            echo '<a href="'. $url . '">'. $this->LoginText . '</a>';
            if ($this->DieOnNoSession) {
                die;
            }
            return false;
        } else {
            return true;
        }
    }
}
