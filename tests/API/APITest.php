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
                        "a" => [
                            "optional" => false
                        ],
                        "required_param" => [
                            "optional" => false
                        ],
                        "optional_param" => [
                            "optional" => true
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
                        "a" => [
                            "optional" => false,
                            "uri" => true
                        ],
                        "b" => [
                            "optional" => false,
                            "uri" => false
                        ],
                        "c" => [
                            "optional" => false
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