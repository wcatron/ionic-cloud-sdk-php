<?php

namespace Ionic\API;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class API implements \Ionic\API\Interfaces\API {
    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * API constructor.
     * @param Route[] $routes
     */
    function __construct($routes) {
        $this->routes = $routes;
    }

    /**
     * @param string $name
     * @param mixed $params
     * @return RequestInterface
     */
    function getRequest($name, $params) {
        return $this->routes[$name]->call($params);
    }

    /**
     * @param string         $name
     * @param Response $results
     * @return callable
     */
    function processOutput($name, Response $results) {
        return $this->routes[$name]->process($results);
    }

}