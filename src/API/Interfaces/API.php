<?php

namespace Ionic\API\Interfaces;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface API {
    /**
     * @param string $name
     * @param mixed $params
     * @return RequestInterface
     */
    function getRequest($name, $params);

    /**
     * @param string         $name
     * @param ResponseInterface $results
     * @return mixed
     */
    function processOutput($name, ResponseInterface $results);
}