<?php

use GuzzleHttp\Psr7\Response;
use Ionic\API\Interfaces\API;
use Ionic\Client;
use Ionic\Helpers\Pagination;
use Ionic\Test\TestingClient;
use Ionic\Users\Models\User;
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
        $this->assertTrue(isset($response['success']));
    }

    function testNonAsyncCall() {
        $client = $this->getTestClientWithResponses([
            new Response(200, [], json_encode(file_get_contents(__DIR__.'/../responses/test.success.json'))),
            new Response(200, [], json_encode(file_get_contents(__DIR__.'/../responses/test.success.json')))
        ]);
        $response = $client->test();
        $this->assertTrue(isset($response['success']));
        $responseAsync = $client->testAsync()->wait();
        $this->assertTrue($response['success'] == $responseAsync['success']);
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
