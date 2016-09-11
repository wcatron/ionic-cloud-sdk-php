<?php

namespace Ionic\Interfaces;

use Ionic\API\Interfaces\API;

interface Client {
    /**
     * @return API
     */
    function getAPI();
    function getDefaults($config = []);
    function __construct($config);

    /**
     * @param       $name
     * @param array $args
     * @return Command
     */
    public function getCommand($name, array $args = [ ]);
}