<?php

namespace Ionic\Interfaces;

use GuzzleHttp\Promise\Promise;

interface Command {
    /**
     * @return Promise
     */
    function resolve();
}