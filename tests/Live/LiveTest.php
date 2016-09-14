<?php

use Ionic\Client;
use Ionic\Helpers\Pagination;
use Ionic\Users\Models\User;
use Ionic\Users\UsersClient;

const LiveTests = false;

class LiveTest extends PHPUnit_Framework_TestCase {
    var $config;
    function setUp() {
        $config = parse_ini_file(__DIR__.'/../config.ini');
        if (LiveTests == false) {
            $this->markTestSkipped("Skipping live tests. That's okay, it should only be used to determine test results.");
        }
        if (empty($config)) {
            $this->markTestSkipped("config.ini file not found so assuming you don't want to test against the real API. That's okay, it should only be used to determine test results.");
        }
        $this->config = $config;
        parent::setUp();
    }

    function testAuthTest() {
        $client = new Client($this->config);
        $response = $client->test();
    }

    function testGetUser() {
        $userClient = new UsersClient($this->config);
        $response = $userClient->getUsers(new Pagination(1,3));
        $this->assertEquals(3, count($response));
        $user = $userClient->getUser($this->config['uuid']);
        $this->assertEquals($this->config['uuid'], $user->uuid);
    }

    function testCreateUser() {
        $this->markTestSkipped("Only use to determine response.");
        $userClient = new UsersClient($this->config);
        $new_user = User::withEmailPassword(time()."@email.com","ABC");
        $new_user = $userClient->createUser($new_user);
        $this->assertTrue(isset($new_user->uuid));
    }

    function testGetCustomData() {
        $userClient = new UsersClient($this->config);
        $data = $userClient->setCustomData($this->config['uuid'], [
            "tested" => time()
        ]);
        print_r($data);
        $data = $userClient->getCustomData($this->config['uuid']);
        print_r($data);
    }

    function testUpdateUser() {
        $new_name = "Updated Name ".time();

        $userClient = new UsersClient($this->config);
        $user = $userClient->getUser($this->config['uuid']);
        $user->details->name = $new_name;
        $user->setChanged('name');
        $user = $userClient->updateUser($user);
        $this->assertEquals($new_name, $user->details->name);
    }

    /**
     * @expectedException \Ionic\Exceptions\AuthorizationException
     */
    function testBadAPI() {
        $client = new Client(['api_token' => 'BADTOKEN','app_id' => 'Fake']);
        $client->test();
    }
}