<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Analytrix\Basic;
use timgws\GoogleAnalytics\API as Analytics;


class NachoTest extends PHPUnit_Framework_TestCase {


    protected $commonStub;
    protected $stub;

    public function setUp()
    {
        date_default_timezone_set('Europe/Stockholm');

        $this->stub = $this->getMockBuilder('Dotenv\Dotenv')
            ->disableOriginalConstructor()
            ->getMock();

        putenv('CLIENT_ID=client_id_123');
        putenv('CLIENT_SECRET=client_secret_123');
        putenv('REDIRECT_URI=redirect_url');
        #$this->stub = $stub;// Configure the stub.$this->commonStub->stub->method('__construct')         ->willReturn('foo');}
    }


    public function testDebugMode()
    {
        $test = new Analytrix\Basic($this->stub, new Analytics);
        $test->DebugMode = true;
        $test->run();
    }

    public function testDebugModeOff()
    {
        $test = new Analytrix\Basic($this->stub, new Analytics);
        $test->DebugMode = true;
        $test->run();
        echo "hello";
    }


    public function tearDown()
    {

        putenv('CLIENT_ID');
        putenv('CLIENT_SECRET');
        putenv('REDIRECT_URI');


    }
}