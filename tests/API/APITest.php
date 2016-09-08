<?php

use Ionic\API\API;

class APITest extends PHPUnit_Framework_TestCase {
    function testAddRoute() {
        $api = new API();
        $api->addRoute('Sample', [
            "http" => [
                "method" => "GET",
                "request_uri" => "/sample"
            ],
            "output" => ["json" => ""]
        ]);
        $request = $api->getRequest('Sample', ["a"=>"foo"]);
        $this->assertEquals("/sample?a=foo",$request->getUri()->__toString());
    }
}