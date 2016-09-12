<?php
/**
 * @deprecated
 */

namespace Ionic\API;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class RouteParser implements \Ionic\API\Interfaces\RouteParser {
    public $config;

    function __construct($config = [ ]) {
        $this->config = $config;
    }

    function parse($routes = null) {
        if (empty($routes)) {
            // TODO: Determine if this should be checked in the constructor.
            if (!empty($this->config['version']) && !empty($this->config['client'])) {
                $routes = json_decode(file_get_contents(__DIR__.'/versions/'.$this->config['version'].'/'.$this->config['client'].'.api.json'), true)['routes'];
            } else if (!empty($this->config['file'])) {
                $routes = json_decode(file_get_contents($this->config['file']), true)['routes'];
            } else {
                throw new \InvalidArgumentException("`file` or `version` and `client` needed in route parser config.");
            }
        }
        return array_map(function ($route) {
            return $this->makeRoute($route);
        }, $routes);
    }

    function makeRoute($route) {
        return new Route(function ($params) use ($route) {
            return self::createRequest($route, $params);;
        }, function (ResponseInterface $response) use ($route) {
            $output = $route['output'];
            return $this->parseOutput($response, $output);
        });
    }

    static function createRequest($route, $params) {
        $request_uri = $route['http']['request_uri'];
        $query_params = [];
        $body_params = [];

        /** @var Parameter[] $parameters */
        $parameters = [];
        foreach ($route['params'] as $definition) {
            Parameter::parseDefinitionToParameters($definition, $parameters);
        }

        $catchall = false;
        foreach ($parameters as $parameter) {
            if ($parameter->name == "*") {
                $catchall = $parameter;
                continue;
            }
            if ($parameter->required) {
                if (!isset($params[$parameter->name])) {
                    throw new \InvalidArgumentException("Request requires `$parameter->name` parameter.");
                }
            }
            switch ($parameter->in) {
                case "path":
                    $request_uri = str_replace("{{$parameter->name}}", $params[$parameter->name], $request_uri);
                    break;
                case "query":
                    $query_params[$parameter->name] = $params[$parameter->name];
                    break;
                case "body":
                    $body_params[$parameter->name] = $params[$parameter->name];
                    break;
            }
            unset($params[$parameter->name]);
        }

        if ($catchall) {
            switch ($catchall->in) {
                case "query":
                    $query_params = array_merge($query_params, $params);
                    break;
                case "body":
                    $body_params = array_merge($body_params, $params);
                    break;
            }
        }

        $uri = new Uri($request_uri);

        if (!empty($query_params)) {
            $uri = $uri->withQuery(http_build_query($query_params));
        }

        $options = [];
        if (!empty($body_params)) {
            $options = ["json" => $body_params];
        }

        $request = new Request(
            $route['http']['method'],
            $uri,
            $options
        );

        return $request;
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