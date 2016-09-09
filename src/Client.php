<?php

namespace Ionic;

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
    }

    /**
     * @param       $name
     * @param array $args
     * @return
     */
    public function getCommand($name, array $args = [ ]) {
        return new \Ionic\Command($name, $args, $this->handler);
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