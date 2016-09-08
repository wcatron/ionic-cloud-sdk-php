<?php

namespace Ionic;

use GuzzleHttp\ClientInterface;
use Ionic\API\API;

class Command implements \Ionic\Interfaces\Command {
    /** @var string */
    private $name;
    /** @var mixed */
    private $data;

    /**
     * @var \Ionic\Interfaces\Client
     */
    private $handler;


    /**
     * Accepts an associative array of command options, including:
     *
     * - @http: (array) Associative array of transfer options.
     *
     * @param string      $name           Name of the command
     * @param array       $args           Arguments to pass to the command
     * @param ClientInterface    $handler        Handler for command
     */
    public function __construct($name, array $args = [], ClientInterface $handler = null)
    {
        $this->name = $name;
        $this->data = $args;
        $this->handler = $handler;
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function resolve() {
        $api = new API();
        $request = $api->getRequest($this->name, $this->data);
        $fetch = $this->handler->sendAsync($request);

        $promise = $fetch->then(function ($results) use ($api) {
            return $api->runParseOutput($this->name, $results);
        });
        return $promise;
    }
}