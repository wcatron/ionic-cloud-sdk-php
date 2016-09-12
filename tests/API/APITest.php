<?php

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Ionic\API\API;
use Ionic\API\RouteParser;
use Ionic\Client;
use Ionic\Test\TestingClient;
use Psr\Http\Message\ResponseInterface;

class APITest extends PHPUnit_Framework_TestCase {
    function testAddRoute() {
        $parser = new RouteParser();
        $routes = $parser->parse(
            [
                "Sample" => [
                    "http"   => [
                        "method"      => "GET",
                        "request_uri" => "/sample"
                    ],
                    "params" => [
                        [
                            "name"     => "a",
                            "required" => true,
                            "in"       => "query"
                        ]
                    ],
                    "output" => [ "json" => "" ]
                ]
            ]);
        $api = new API($routes);
        $request = $api->getRequest('Sample', [ "a" => "foo" ]);
        $this->assertEquals("/sample?a=foo", $request->getUri()->__toString());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Request requires `required_param` parameter.
     */
    function testRequiredParameter() {
        $parser = new RouteParser();
        $routes = $parser->parse(
            [
                "Sample" => [
                    "http"   => [
                        "method"      => "GET",
                        "request_uri" => "/sample"
                    ],
                    "params" => [
                        [
                            "name"     => "a",
                            "in"       => "query",
                            "required" => true
                        ],
                        [
                            "name"     => "required_param",
                            "in"       => "query",
                            "required" => true
                        ],
                        [
                            "name"     => "optional_param",
                            "in"       => "query",
                            "required" => false
                        ]
                    ],
                    "output" => [ "json" => "" ]
                ]
            ]);
        $api = new API($routes);
        $request = $api->getRequest('Sample', [ "a" => "foo" ]);
    }

    function testUriParam() {
        $parser = new RouteParser();
        $routes = $parser->parse(
            [
                "Sample" => [
                    "http"   => [
                        "method"      => "GET",
                        "request_uri" => "/sample/{a}"
                    ],
                    "params" => [
                        [
                            "name"     => "a",
                            "required" => true,
                            "in"       => "path"
                        ],
                        [
                            "name"     => "b",
                            "required" => true,
                            "in"       => "query"
                        ],
                        [
                            "name"     => "c",
                            "required" => true,
                            "in"       => "query"
                        ]
                    ],
                    "output" => [ "json" => "" ]
                ]
            ]);
        $api = new API($routes);
        $request = $api->getRequest('Sample', [ "a" => "fee", "b" => "fi", "c" => "fo" ]);
        $this->assertEquals("/sample/fee?b=fi&c=fo", $request->getUri()->__toString());
    }

    use TestingClient;

    function testCustomAPI() {
        $api = new CustomAPI();
        $client = $this->getTestClientWithResponses([
                                                       new Response(200, [ ], "XXX"),
                                                       new Response(200, [ ], "YYY")
                                                   ], Client::class, [
                                                       'api' => $api
                                                   ]);
        $responseA = $client->getCommand('foo')->resolve()->wait();
        $responseB = $client->getCommand('fi')->resolve()->wait();
        $this->assertEquals("Custom Output: foo XXX", $responseA);
        $this->assertEquals("Custom Output: fi YYY", $responseB);
    }
}

class CustomAPI implements \Ionic\API\Interfaces\API {
    function getRequest($name, $params) {
        return new Request('get', '/');
    }

    function processOutput($name, ResponseInterface $results) {
        return "Custom Output: ".$name." ".$results->getBody();
    }
}