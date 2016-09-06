<?php

namespace Ionic\Users;

use Ionic\Helpers\Pagination;
use Ionic\Interfaces\Client;
use Ionic\Users\Models\User;

class UsersClient {
    /**
     * @var Client
     */
    private $client;
    function __construct($client) {
        $this->client = $client;
    }

    /**
     * @param $pagination Pagination
     * @return User[]
     */
    function getUsers(&$pagination) {
        $response = $this->client->getUsers($pagination->pageSize, $pagination->page);
        $pagination->currentSize = count($response);
        $pagination->page++;
        return [];
    }


}