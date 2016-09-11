<?php

namespace Ionic\API;

use Ionic\API\Interfaces\Route as RouteInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class API implements \Ionic\API\Interfaces\API {
    /**
     * @var RouteInterface[]
     */
    private $routes = [];

    /**
     * API constructor.
     * @param RouteInterface[] $routes
     */
    function __construct($routes = []) {
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
     * @param ResponseInterface $results
     * @return callable
     */
    function processOutput($name, ResponseInterface $results) {
        return $this->routes[$name]->process($results);
    }

    /**
     * @param $name
     * @return RouteInterface
     */
    function getRoute($name) {
        return $this->routes[$name];
    }

}