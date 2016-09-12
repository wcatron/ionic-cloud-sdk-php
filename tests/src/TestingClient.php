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
    function getTestClientWithResponse($response, $clientClass = Client::class, $config = []) {
        return $this->getTestClientWithResponses([ $response ], $clientClass, $config);
    }

    /**
     * @param $responses
     * @param $clientClass
     * @return Client
     */
    function getTestClientWithResponses($responses, $clientClass = Client::class, $config = []) {
        $mock = new MockHandler($responses);
        $handler = new HandlerStack($mock);
        $mockClient = new \GuzzleHttp\Client([ 'handler' => $handler ]);

        return new $clientClass(
            array_replace_recursive([
                'http_handler' => $mockClient,
                'api_token'    => 'XXXXX',
                'app_id'       => 'XXXXX'
            ], $config));
    }
}