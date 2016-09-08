<?php

namespace Ionic\Helpers;

use GuzzleHttp\Client;

class HttpHandler extends Client {
    function __construct(array $config) {
        $config['base_uri'] = "https://api.ionic.io/";
        if (empty($config['api_token'])) {
            throw new \InvalidArgumentException("Must set 'api_token'.");
        }
        if (!isset($config['headers'])) {
            $config['headers'] = [];
        }
        $config['headers']['Authorization'] = "Bearer ".$config['api_token'];

        parent::__construct($config);
    }
}