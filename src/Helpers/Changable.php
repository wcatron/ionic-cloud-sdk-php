<?php

namespace Ionic\Helpers;

interface Changable {
    /**
     * @return mixed Array of keys and values to update on server.
     */
    function changes();
}