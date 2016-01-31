<?php 


namespace Analytrix;

use timgws\GoogleAnalytics\API as Analytics;


class Basic {

    var $dotenv;
    var $ga;
    var $storage;

    private $data = array();

    function __construct( $dotenv =  \Dotenv\Dotenv, $ga = Analytics, $storage )
    {
        $this->dotenv = $dotenv;
        $this->ga = $ga;
        $this->storage = $storage;

        $this->dotenv->load();
        $this->dotenv->required(array('CLIENT_ID', 'CLIENT_SECRET', 'REDIRECT_URI'));

        $this->data['LoginText'] = 'login here';
        $this->data['DieOnNoSession'] = false;
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

        $this->ga->auth->setClientId( getenv( 'CLIENT_ID' ) );
        $this->ga->auth->setClientSecret( getenv( 'CLIENT_SECRET' ) );  
        $this->ga->auth->setRedirectUri( getenv( 'REDIRECT_URI' ) );  

        // Try to get the AccessToken
        if ( isset( $_GET['code'] ) ) {
            $code = $_GET['code'];
            $auth = $this->ga->auth->getAccessToken( $code );

            if ( $auth['http_code'] == 200 ) {
                echo '<pre>';
                var_dump($auth);
                echo '</pre>';
                $accessToken = $auth['access_token'];
                $refreshToken = $auth['refresh_token'];
                $tokenExpires = $auth['expires_in'];
                $tokenCreated = time();
#                $_SESSION['auth'] = $auth;
                $this->storage->set('auth',$auth);
                $this->storage->set('refreshToken',$refreshToken);
                $this->storage->set('tokenExpires',$tokenExpires);
                $this->storage->set('tokenCreated',$tokenCreated);
            }
        }

        if ( empty($this->storage->get('auth'))  ) {
            $url = $this->ga->auth->buildAuthUrl();
            echo '<a href="'. $url . '">'. $this->LoginText . '</a>';
            if ($this->DieOnNoSession) {
                die;
            }
            return false;
        } else {
		    if (isset($auth))
	                    var_dump($auth);
                    $refreshToken = $this->storage->get( 'refreshToken' );
                    $tokenExpires = $this->storage->get( 'tokenExpires' );
                    $tokenCreated = $this->storage->get( 'tokenCreated' );


                    $all_data = $this->storage->get_all();

                    var_dump($all_data);
                    echo "refreshtoken '" . $refreshToken ."'\n<br>";
                    echo "tokenExpires '" . $tokenExpires. "'\n<br>";
                    echo "tokenCreated '" . $tokenCreated . "'\n<br>";

            // Check if the accessToken is expired
                    if ((time() - $tokenCreated) >= $tokenExpires)
                    {

                        echo "it ahz expired\n";

                        // update token data
                        $auth = $this->ga->auth->refreshAccessToken($refreshToken);
                        var_dump($auth);
                        $accessToken = $auth['access_token'];
                        $refreshToken = $auth['refresh_token'];
                        $tokenExpires = $auth['expires_in'];
                        $tokenCreated = time();
                        $this->storage->set('auth',$auth);
#                        $this->storage->set('refreshToken',$refreshToken);
                        $this->storage->set('tokenExpires',$tokenExpires);
                        $this->storage->set('tokenCreated',$tokenCreated);


                    }
#            die;

            return true;
        }
    }
}
