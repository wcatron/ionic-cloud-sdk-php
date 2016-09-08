<?php

namespace Ionic\API;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class API {
    private $routes = [];
    function __construct() {
        $routes = json_decode(file_get_contents(__DIR__.'/api.json'), true)['routes'];
        $this->routes = $routes;
        foreach ($routes as $name => $route) {
            $this->addRoute($name, $route);
        }
    }

    function addRoute($name, $route) {
        $uri = $this->processUri($route['http']['request_uri']);
        $output = $route['output'];
        $requestCallable = function ($params) use ($route, $uri) {
            $uri = new Uri($uri);
            $uri = $uri->withQuery(http_build_query($params));
            $request = new Request(
                $route['http']['method'],
                $uri
            );
            return $request;
        };
        $processCallable = function (Response $response) use ($output) {
            return $this->parseOutput($response, $output);
        };
        $this->routes[ $name ] = [ "request" => $requestCallable, "output" => $processCallable ];
    }

    /**
     * @param $name
     * @param $params
     * @return RequestInterface
     */
    function getRequest($name, $params) {
        return $this->routes[$name]['request']($params);
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

    function runParseOutput($name, Response $results) {
        return $this->routes[$name]['output']($results);
    }

    function processUri($uri) {
        return $uri;
    }
}