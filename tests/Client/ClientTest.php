<?php

use GuzzleHttp\Psr7\Response;
use Ionic\Client;
use Ionic\Helpers\Pagination;
use Ionic\Test\TestingClient;
use Ionic\Users\UsersClient;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/6/16
 * Time: 6:57 PM
 */
class ClientTest extends PHPUnit_Framework_TestCase {

    use TestingClient;

    function testTest() {
        $client = $this->getTestClientWithResponse(new Response(200, [], json_encode(file_get_contents(__DIR__.'/../responses/test.success.json'))));
        $response = $client->test();
        $this->assertTrue(isset($response['data']['success']));
    }

    function testTestAuth() {
        $config = parse_ini_file(__DIR__.'/../config.ini');
        if (empty($config) || true) {
            $this->markTestSkipped("config.ini file not found so assuming you don't want to test real API. That's okay, it's it should only be used to determine test results.");
        }
        $client = new Client($config);
        $response = $client->test();
        print_r($response);
        $userClient = new UsersClient($config);
        $response = $userClient->getUsers(new Pagination(1,3));
        $this->assertEquals(3, count($response));
    }

}
