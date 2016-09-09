<?php

use Ionic\API\API;
use Ionic\API\RouteParser;

class APITest extends PHPUnit_Framework_TestCase {
    function testAddRoute() {
        $parser = new RouteParser();
        $routes = $parser->parse([
            "Sample" => [
                "http" => [
                    "method" => "GET",
                    "request_uri" => "/sample"
                ],
                "output" => ["json" => ""]
            ]
                       ]);
        $api = new API($routes);
        $request = $api->getRequest('Sample', ["a"=>"foo"]);
        $this->assertEquals("/sample?a=foo",$request->getUri()->__toString());
    }
}