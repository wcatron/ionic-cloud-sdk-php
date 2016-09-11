<?php

use Ionic\API\API;
use Ionic\API\RouteParser;

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
                            "name" => "a",
                            "required" => true,
                            "in" => "query"
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
                            "name" => "a",
                            "in" => "query",
                            "required" => true
                        ],
                        [
                            "name" => "required_param",
                            "in" => "query",
                            "required" => true
                        ],
                        [
                            "name" => "optional_param",
                            "in" => "query",
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
                            "name" => "a",
                            "required" => true,
                            "in" => "path"
                        ],
                        [
                            "name" => "b",
                            "required" => true,
                            "in" => "query"
                        ],
                        [
                            "name" => "c",
                            "required" => true,
                            "in" => "query"
                        ]
                    ],
                    "output" => [ "json" => "" ]
                ]
            ]);
        $api = new API($routes);
        $request = $api->getRequest('Sample', [ "a" => "fee" , "b" => "fi", "c" => "fo"]);
        $this->assertEquals("/sample/fee?b=fi&c=fo", $request->getUri()->__toString());
    }
}