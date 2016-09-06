<?php

namespace Ionic\Package;

use Ionic\Interfaces\Client;

class PackageClient {
    /**
     * @var Client
     */
    private $client;
    function __construct($client) {
        $this->client = $client;
    }
}