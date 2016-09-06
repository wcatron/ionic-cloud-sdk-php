<?php

namespace Ionic\Deploy;

use Ionic\Interfaces\Client;

class DeployClient {
    /**
     * @var Client
     */
    private $client;
    function __construct($client) {
        $this->client = $client;
    }
}