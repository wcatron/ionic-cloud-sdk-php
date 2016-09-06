<?php
use Ionic\Interfaces\Client;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/6/16
 * Time: 6:57 PM
 */
class ClientTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Client
     */
    private $client;
    function setUp() {
        parent::setUp();
        try {
            $config = parse_ini_file(__DIR__.'/../config.ini');
        } catch (\Exception $e) {
            $this->markTestSkipped('Test skipped since no config was found.');
        }
        $this->client = new Ionic\Client($config);
    }

    function testClientTest() {
        print_r($this->client->test());
    }

    function testClientGetUsers() {
        $users = $this->client->getUsers(10,0);
        var_dump($users);
    }
}
