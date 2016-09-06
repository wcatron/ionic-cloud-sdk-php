<?php

namespace Ionic\Push;

use Ionic\Interfaces\Client;

class PushClient {
    /**
     * @var Client
     */
    private $client;
    function __construct(Client $client) {
        $this->client = $client;
    }
}