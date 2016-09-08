<?php

namespace Ionic\Interfaces;

interface Client {
    function __construct($config);
    public function getCommand($name, array $args = [ ]);
}