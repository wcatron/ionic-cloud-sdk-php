<?php

namespace Ionic;

use GuzzleHttp\ClientInterface;
use Ionic\API\Interfaces\API as APIInterface;

class Command implements \Ionic\Interfaces\Command {
    /** @var string */
    private $name;
    /** @var mixed */
    private $data;

    /**
     * @var \Ionic\Interfaces\Client
     */
    private $client;
    /**
     * @var APIInterface
     */
    private $api;


    /**
     * Accepts an associative array of command options, including:
     *
     * - @http: (array) Associative array of transfer options.
     *
     * @param string            $name       Name of the command
     * @param array             $args       Arguments to pass to the command
     * @param ClientInterface   $handler    Handler for command
     * @param APIInterface               $api        API
     */
    public function __construct($name, array $args = [], ClientInterface $handler = null, APIInterface $api)
    {
        $this->name = $name;
        $this->data = $args;
        $this->client = $handler;
        $this->api = $api;
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function resolve() {
        $request = $this->api->getRequest($this->name, $this->data);
        $fetch = $this->client->sendAsync($request);

        $promise = $fetch->then(function ($results) {
            return $this->api->processOutput($this->name, $results);
        });
        return $promise;
    }
}