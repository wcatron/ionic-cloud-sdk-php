<?php

namespace Ionic\Interfaces;

use GuzzleHttp\Promise\PromiseInterface;

interface Command {
    /**
     * @return PromiseInterface
     */
    function resolve();
}