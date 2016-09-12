<?php

namespace Ionic\Helpers;

use GuzzleHttp\Client;

class HttpHandler extends Client {
    const BaseURI = "https://api.ionic.io/";
    function __construct(array $config) {
        $config['base_uri'] = self::BaseURI;

        if (!isset($config['headers'])) {
            $config['headers'] = [];
        }

        $config['headers']['Authorization'] = "Bearer ".required('api_token', $config);

        parent::__construct($config);
    }
}