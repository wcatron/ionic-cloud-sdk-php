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

    function parse($routes = []) {
        if (!empty($this->config['file'])) {
            $routes = self::getRouteFromFile($this->config['file'], $routes);
        } else if (!empty($this->config['version']) && !empty($this->config['client'])) {
            try {
                $routes = self::getRouteFromFile(__DIR__.'/versions/'.$this->config['version'].'/'.$this->config['client'].'.api.json', $routes);
            } catch (\OutOfBoundsException $e) {
                throw new \Exception('Version '.$this->config['version'].' does not exist or client '.$this->config['client'].' is wrong.', 0, $e);
            }
        } else if (empty($routes)) {
            throw new \InvalidArgumentException("`file` or `version` and `client` needed in route parser config.");
        }
        return array_map(function ($route) {
            return $this->makeRoute($route);
        }, $routes);
    }

    static function getRouteFromFile($file, $routes = []) {
        if (file_exists($file)) {
            return array_merge(json_decode(file_get_contents($file), true)['routes'], $routes);
        }
        throw new \OutOfBoundsException('Route file does not exist.');
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
            if (empty($params[$parameter->name])) {
                if ($parameter->required) {
                    throw new \InvalidArgumentException("Request requires `$parameter->name` parameter.");
                } else {
                    continue;
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

        $request = new Request(
            $route['http']['method'],
            $uri);

        if (!empty($body_params)) {
            $stream = \GuzzleHttp\Psr7\stream_for(json_encode($body_params));
            $request = $request->withBody($stream)->withHeader('Content-Type', 'application/json');
        }

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
            return (is_string($response)) ? json_decode($response, true)['data'] : $response['data'];
        }
        throw new \Exception('Unknown output option.');
    }
}