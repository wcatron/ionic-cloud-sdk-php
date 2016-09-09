<?php

namespace Ionic\Users;

use GuzzleHttp\Promise\Promise;
use Ionic\API\RouteParser;
use Ionic\Client;
use Ionic\Helpers\Pagination;
use Ionic\Users\Models\User;

/**
 * Class UsersClient
 * @package Ionic\Users
 * @method User createUser(User $user)
 * @method User getUser(string $uuid)
 * @method User updateUser(User $user)
 * @method void deleteUser(string $uuid)
 * @method mixed getCustomData(string $uuid)
 * @method mixed updateCustomData(string $uuid, mixed $data)
 */
class UsersClient extends Client {

    function __construct($config) {
        if (empty($config['route_parser'])) {
            $config['route_parser'] = new RouteParser(["file" => __DIR__."/../API/users.api.json"]);
        }
        parent::__construct($config);
    }

    /**
     * @param $pagination Pagination
     * @return User[]
     * @internal Pass by reference requires this function be implemented fully vs called via __call()
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

    /**
     * @param User $user
     * @return Promise
     */
    function createUserAsync(User $user) {
        // TODO: Implement create user.
    }

    /**
     * @param $uuid
     * @return Promise
     */
    function getUserAsync($uuid) {
        return $this->getCommand('getUser', ['user_uuid' => $uuid])->resolve();
    }

    /**
     * @param User $user
     * @return Promise
     */
    function updateUserAsync($user) {
        // TODO: Implement update user.
    }

    /**
     * @param $uuid
     * @return Promise
     */
    function deleteUserAsync($uuid) {
        return $this->getCommand('deleteUser', ['user_uuid' => $uuid])->resolve();
    }

    /**
     * @param string $uuid
     * @return Promise
     */
    function getCustomDataAsync($uuid) {
        return $this->getCommand('getCustomData', ['user_uuid' => $uuid])->resolve();
    }

    /**
     * @param string $uuid
     * @param mixed $data
     * @return Promise
     */
    function updateCustomDataAsync($uuid, $data) {
        return $this->getCommand('updateCustomData', ['user_uuid' => $uuid, 'data' => $data])->resolve();
    }

}