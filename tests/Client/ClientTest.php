<?php

use GuzzleHttp\Psr7\Response;
use Ionic\API\Interfaces\API;
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

    function testNonAsyncCall() {
        $client = $this->getTestClientWithResponses([
            new Response(200, [], json_encode(file_get_contents(__DIR__.'/../responses/test.success.json'))),
            new Response(200, [], json_encode(file_get_contents(__DIR__.'/../responses/test.success.json')))
        ]);
        $response = $client->test();
        $this->assertTrue(isset($response['data']['success']));
        $responseAsync = $client->testAsync()->wait();
        $this->assertTrue($response['data']['success'] == $responseAsync['data']['success']);
    }

    function testTestAuth() {
        $config = parse_ini_file(__DIR__.'/../config.ini');
        if (empty($config) || false) {
            $this->markTestSkipped("config.ini file not found so assuming you don't want to test real API. That's okay, it's it should only be used to determine test results.");
        }
        $client = new Client($config);
        $response = $client->test();
        print_r($response);
        $userClient = new UsersClient($config);
        $response = $userClient->getUsers(new Pagination(1,3));
        $this->assertEquals(3, count($response));
        $user = $userClient->getUser('c4c0eac7-a20f-4f57-aa63-9fbcebe7513a');
        $this->assertEquals('c4c0eac7-a20f-4f57-aa63-9fbcebe7513a', $user->uuid);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide API object or Class for `api` in config.
     */
    function testBadAPIConfig() {
        $this->getTestClientWithResponses([], Client::class, [
            'api' => 'Invalid Argument - strings are not allowed.'
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide RouteParser object or class for `route_parser` in config when using the api[class] configuration method.
     */
    function testBadRouteParserConfig() {
        $this->getTestClientWithResponses([],Client::class, [
            'route_parser' => 'Invalid Argument - strings are not allowed.'
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide HTTPHandler or Class for `http_handler` in config.
     */
    function testBadHTTPHandlerConfig() {
        $this->getTestClientWithResponses([],Client::class, [
            'http_handler' => 'Invalid Argument - strings are not allowed.'
        ]);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    function testBadFunctionCall() {
        $client = $this->getTestClientWithResponses([]);
        $response = $client->badMagicCall();
    }

    function testGetAPI() {
        $client = $this->getTestClientWithResponses([]);
        $this->assertTrue($client->getAPI() instanceof API);
    }

}
