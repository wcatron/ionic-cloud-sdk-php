<?php

namespace Ionic\Test;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Ionic\Client;

trait TestingClient {
    /**
     * @param $response
     * @param $clientClass
     * @return Client
     */
    static function getTestClientWithResponse($response, $clientClass = Client::class, $config = []) {
        return static::getTestClientWithResponses([ $response ], $clientClass, $config);
    }

    /**
     * @param $responses
     * @param $clientClass
     * @return Client
     */
    static function getTestClientWithResponses($responses, $clientClass = Client::class, $config = []) {
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

    static function createTestClient($clientClass, $responseFiles = [], $responseCodes = []) {
        $responses = [];
        foreach ($responseFiles as $index => $responseFile) {
            $code = $responseCodes[$index];
            $data = json_encode(file_get_contents(__DIR__.'/../responses/'.$responseFile));
            $response = new Response($responseCodes[$index],
                                     [],
                                     $data);
            if ($code < 300) {
                array_push($responses,
                           $response);
            } else {
                $exception = new RequestException("Error...", new Request('GET','/error'), $response);

                array_push($responses, $exception);
            }

        }
        return static::getTestClientWithResponses($responses, $clientClass);
    }
}