<?php

namespace Ionic\Test;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Ionic\Client;

trait TestingClient {
    /**
     * @param $response
     * @param $clientClass
     * @return Client
     */
    function getTestClientWithResponse($response, $clientClass = Client::class) {
        return $this->getTestClientWithResponses([ $response ], $clientClass);
    }

    /**
     * @param $responses
     * @param $clientClass
     * @return Client
     */
    function getTestClientWithResponses($responses, $clientClass = Client::class) {
        $mock = new MockHandler($responses);
        $handler = new HandlerStack($mock);
        $mockClient = new \GuzzleHttp\Client([ 'handler' => $handler ]);

        return new $clientClass(
            [
                'http_handler' => $mockClient,
                'api_token'    => 'XXXXX',
                'app_id'       => 'XXXXX'
            ]);
    }
}