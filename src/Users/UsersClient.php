<?php

namespace Ionic\Users;

use GuzzleHttp\Promise\Promise;
use Ionic\Client;
use Ionic\Helpers\Pagination;
use Ionic\Users\Models\User;

class UsersClient extends Client {
    /**
     * @param $pagination Pagination
     * @return User[]
     */
    function getUsers(Pagination &$pagination) {
        $results = $this->getUsersAsync($pagination)->wait();
        return $results;
    }

    /**
     * @param Pagination $pagination
     * @return Promise
     */
    function getUsersAsync(Pagination &$pagination) {
        return $this->getCommand('getUsers', ['page_size' => $pagination->pageSize, 'page' => $pagination->page])->resolve()->then(function ($results) use (&$pagination) {
            $pagination->currentSize = count($results);
            $pagination->currentPage++;
            return $results;
        });
    }
}