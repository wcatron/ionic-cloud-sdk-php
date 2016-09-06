<?php

namespace Ionic\Test;

use Ionic\Helpers\Pagination;
use Ionic\Interfaces\Client;

class TestClient implements Client {
    /**
     * TestClient constructor.
     * @param $config array Can be empty for testing.
     */
    function __construct($config) {

    }

    function test() {

    }

    /**
     * @param $page_size int Sets the number of items to return in paginated endpoints
     * @param $page int Sets the page number for paginated endpoints
     * @return array
     */
    function getUsers($page_size, $page) {
        $users = [
            [
                'app_id' => 'testing',
                'created' => '',
                'custom' => null
            ]
        ];
        return array_splice($users, $page_size * $page, $page_size);
    }
}