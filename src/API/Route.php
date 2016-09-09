<?php

namespace Ionic\API;

class Route implements \Ionic\API\Interfaces\Route {
    private $request;
    private $process;

    function __construct(callable $request, callable $process) {
        $this->request = $request;
        $this->process = $process;
    }

    function call($params) {
        return call_user_func($this->request, $params);
    }
    function process($results) {
        return call_user_func($this->process, $results);
    }
}