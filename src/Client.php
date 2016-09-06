<?php

namespace Ionic;

use Ionic\Helpers\HttpHandler;

class Client implements \Ionic\Interfaces\Client {
    private $config;
    private $api;
    private $api_token;
    function __construct($config) {
        $this->config = $config;
        $this->api_token = $config['api_token'];
        $this->api = new HttpHandler();
    }

    function test() {
        $response = $this->api->call('GET', 'auth/test', [], $this->api_token);
        return $response;
    }

    function getUsers($page_size, $page) {
        // TODO: Implement getUsers() method.
    }

}