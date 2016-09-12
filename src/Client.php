<?php

namespace Ionic;

use GuzzleHttp\ClientInterface;
use Ionic\API\Interfaces\API;
use GuzzleHttp\Promise\Promise;
use Ionic\API\Interfaces\RouteParser;

/**
 * @method mixed test()
 **/
class Client implements \Ionic\Interfaces\Client {
    private $config;
    /**
     * @var ClientInterface
     */
    public $handler;
    /**
     * @var string
     */
    private $api_token;
    /**
     * @var string
     */
    public $app_id;
    /**
     * @var RouteParser
     */
    private $route_parser;
    /**
     * @var API
     */
    private $api;

    // Prefered way to store defaults, however class arrays currently do not work in hhvm.
    // const Defaults = [...];

    function getDefaults($config = []) {
        return array_replace_recursive([
            'http_handler' => [
                'class' => \Ionic\Helpers\HTTPHandler::class
            ],
            'route_parser' => [
                'class' => \Ionic\API\RouteParser::class,
                'configs' => [
                    'file' => __DIR__."/API/api.json"
                ]
            ],
            'api' => [
                'class' => \Ionic\API\API::class
            ]
        ], $config);
    }

    /**
     * Client constructor.
     * @param $config
     * Configuration array with the following options:
     * - api_token string required Ionic Cloud API token. TODO: Get url to link to documentation to get this.
     * - app_id string required Ionic Cloud App ID
     * - http_handler ClientInterface
     */
    function __construct($config) {
        $config = $this->getDefaults($config);

        $this->config = $config;
        $this->api_token = required('api_token', $config);
        $this->app_id = required('app_id', $config);

        if (is_object($config['http_handler'])) {
            $this->handler = $config['http_handler'];
        } else if (is_array($config['http_handler'])) {
            $class = $config['http_handler']['class'];
            $this->handler = new $class(
                [
                    'api_token' => $this->api_token
                ]);
        } else {
            unset($config['http_handler']);
            required('http_handler', $config, 'Must provide HTTPHandler or Class for `{param}` in config.');
        }

        if (is_object($config['route_parser'])) {
            $this->route_parser = $config['route_parser'];
        } else if (is_array($config['route_parser'])) {
            $class = $config['route_parser']['class'];
            $this->route_parser = new $class($config['route_parser']['configs']);
        } else {
            unset($config['route_parser']);
        }

        if (is_object($config['api'])) {
            $this->api = $config['api'];
        } else if (is_array($config['api'])) {
            $class = $config['api']['class'];
            required('route_parser', $config, 'Must provide RouteParser object or class for `{param}` in config when using the api[class] configuration method.');
            $this->api = new $class($this->route_parser->parse());
        } else {
            unset($config['api']);
            required('api', $config, 'Must provide API object or Class for `{param}` in config.');
        }
    }

    public function getCommand($name, array $args = [ ]) {
        return new Command($name, $args, $this->handler, $this->api);
    }

    public function getAPI() {
        return $this->api;
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
        throw new \BadMethodCallException();
    }
}