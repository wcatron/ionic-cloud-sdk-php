<?php
use Ionic\Helpers\HttpHandler;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/8/16
 * Time: 1:36 PM
 */
class HttpHandlerTest extends PHPUnit_Framework_TestCase {
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage api_token is required in configs.
     */
    function testConfigException () {
        $handler = new HttpHandler([]);
    }

    function testConfigComplete() {
        $handler = new HttpHandler(['api_token' => 'XXX']);
    }
}
