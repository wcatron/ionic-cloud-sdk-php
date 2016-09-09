<?php

namespace Ionic\API\Interfaces;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

interface API {
    /**
     * @param string $name
     * @param mixed $params
     * @return Request
     */
    function getRequest($name, $params);

    /**
     * @param string         $name
     * @param Response $results
     * @return mixed
     */
    function processOutput($name, Response $results);
}