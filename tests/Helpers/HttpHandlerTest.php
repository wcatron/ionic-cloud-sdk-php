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
     */
    function testConfigException () {
        $handler = new HttpHandler([]);
    }
}
