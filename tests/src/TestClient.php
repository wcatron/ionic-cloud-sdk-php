<?php

namespace Ionic\Test;

use Ionic\Helpers\Pagination;
use Ionic\Interfaces\Client;

class TestClient implements Client {
    function __construct($config) {

    }

    /**
     * @param $page_size int Sets the number of items to return in paginated endpoints
     * @param $page int Sets the page number for paginated endpoints
     * @return array
     */
    function getUsers($page_size, $page) {
        return [ ];
    }
}