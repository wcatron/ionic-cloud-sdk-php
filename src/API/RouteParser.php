<?php

namespace Ionic\API;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;

class RouteParser implements \Ionic\API\Interfaces\RouteParser {
    public $config;

    function __construct($config = [ ]) {
        $this->config = $config;
    }

    function parse($routes = null) {
        if (empty($routes)) {
            // TODO: Determine if this should be checked in the constructor.
            if (empty($this->config['file'])) {
                throw new \InvalidArgumentException("`file` needed in route parser config.");
            }
            $routes = json_decode(file_get_contents($this->config['file']), true)['routes'];
        }
        return array_map(function ($route) {
            return $this->makeRoute($route);
        }, $routes);
    }

    function makeRoute($route) {
        return new Route(function ($params) use ($route) {
            $uri = new Uri($route['http']['request_uri']);
            $uri = $uri->withQuery(http_build_query($params));
            $request = new Request(
                $route['http']['method'],
                $uri
            );
            return $request;
        }, function (Response $response) use ($route) {
            $output = $route['output'];
            return $this->parseOutput($response, $output);
        });
    }

    /**
     * @param $response Response
     * @return mixed
     * @throws \Exception
     */
    function parseOutput($response, $config) {
        $response = json_decode($response->getBody(), true);
        if (isset($config['array'])) {
            $returnArray = [];
            foreach ($response['data'] as $element) {
                $class = $config['array'];
                array_push($returnArray, new $class($element));
            }
            return $returnArray;
        } else if (isset($config['object'])) {
            $class = $config['object'];
            return new $class($response['data']);
        } else if (isset($config['json'])) {
            return (is_string($response)) ? json_decode($response, true) : $response;
        }
        throw new \Exception('Unknown output option.');
    }
}