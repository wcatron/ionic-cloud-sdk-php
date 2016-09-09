<?php

namespace Ionic\API\Interfaces;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

interface Route {
    /**
     * @param $params
     * @return Request
     */
    function call($params);

    /**
     * @param Response $results
     * @return mixed
     */
    function process($results);
}