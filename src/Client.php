<?php

namespace Ionic;

use Ionic\API\API;
use Ionic\API\RouteParser;
use GuzzleHttp\Promise\Promise;
use Ionic\Helpers\HttpHandler;

/**
 * @method mixed test()
 **/
class Client implements \Ionic\Interfaces\Client {
    private $config;
    /**
     * @var HttpHandler
     */
    public $handler;
    private $api_token;
    public $app_id;
    public $api;

    function __construct($config) {
        $this->config = $config;
        $this->api_token = $config['api_token'];
        $this->app_id = $config['app_id'];
        if (empty($config['http_handler'])) {
            $this->handler = new HttpHandler(
                [
                    'api_token' => $this->api_token
                ]);
        } else {
            $this->handler = $config['http_handler'];
        }

        if (empty($config['route_parser'])) {
            $config['route_parser'] = new RouteParser(["file" => __DIR__."/API/api.json"]);
        }
        if (empty($config['api'])) {
            $config['api'] = new API($config['route_parser']->parse());
        }
        $this->api = $config['api'];
    }

    /**
     * @param       $name
     * @param array $args
     * @return
     */
    public function getCommand($name, array $args = [ ]) {
        return new \Ionic\Command($name, $args, $this->handler, $this->api);
    }

    /**
     * @return Promise
     */
    public function testAsync() {
        return $this->getCommand('test')->resolve();
    }

    function __call($name, $arguments) {
        if (method_exists($this, $name.'Async')) {
            /** @var Promise $promise */
            $promise = call_user_func_array([$this, $name.'Async'], $arguments);
            return $promise->wait();
        }
    }
}