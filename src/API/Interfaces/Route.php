<?php

namespace Ionic\API\Interfaces;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Route {
    /**
     * @param $params
     * @return RequestInterface
     */
    function call($params);

    /**
     * @param ResponseInterface $results
     * @return mixed
     */
    function process($results);
}