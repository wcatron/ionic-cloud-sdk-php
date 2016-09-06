<?php

namespace Ionic;

class Client implements \Ionic\Interfaces\Client {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }

    function getUsers($page_size, $page) {
        // TODO: Implement getUsers() method.
    }
}