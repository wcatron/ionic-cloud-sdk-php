<?php

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Ionic\API\Interfaces\RouteParser;
use Ionic\API\Route;
use Ionic\Client;
use Ionic\Interfaces\Command;
use Ionic\Test\TestingClient;
use Psr\Http\Message\ResponseInterface;

class RouteParserTest extends PHPUnit_Framework_TestCase {

    use TestingClient;

    function testInjectRouteParser() {

        $response = new Response(200);
        $parser = new CustomRouteParser();
        $client = $this->getTestClientWithResponse($response, Client::class, [
            'route_parser' => $parser
        ]);

        $command = $client->getCommand('test');
        $response = $command->resolve()->wait();
        $this->assertEquals("success", $response);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Must provide RouteParser object or class for `route_parser` in config when using the api[class] configuration method.
     */
    function testInvalidRouteParser() {
        $client = new Client([
                                 'route_parser' => null,
                                 'app_id' => 'XXX',
                                 'api_token' => 'XXX'
                             ]);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Version a.b.c-alpha does not exist or client base is wrong.
     */
    function testInvalidVersion() {
        $client = new Client([
                                 'route_parser' => [
                                    'configs' => [
                                        'version' => 'a.b.c-alpha',
                                        'client' => 'base'
                                    ]
                                 ],
                                 'app_id' => 'XXX',
                                 'api_token' => 'XXX'
                             ]);
    }

    function testParseFromFile() {
        $client = new Client([
                                 'route_parser' => [
                                     'configs' => [
                                         'file' => __DIR__.'/routes.test.json'
                                     ]
                                 ],
                                 'app_id' => 'XXX',
                                 'api_token' => 'XXX'
                             ]);
        $this->assertTrue($client->getCommand('test') instanceof Command);
    }
}

class CustomRouteParser implements RouteParser {
    function parse () {
        $route = new Route(function () {
            return new Request('get', '/');
        }, function (ResponseInterface $response) {
            return "success";
        });
        return ["test" => $route];
    }
}