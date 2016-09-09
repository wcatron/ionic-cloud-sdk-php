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

    /**
     * @param User $user
     * @return User
     */
    function createUser(User $user) {
        // TODO: Implement create user.
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
     * @return User
     */
    function getUser($uuid) {
        return $this->getUserAsync($uuid)->wait();
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
     * @return User
     */
    function updateUser($user) {
        // TODO: Implement update user.
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
     */
    function deleteUser($uuid) {
        return $this->deleteUserAsync($uuid)->wait();
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
     * @return mixed
     */
    function getCustomData($uuid) {
        return $this->getCustomDataAsync($uuid)->wait();
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
     * @return mixed
     */
    function updateCustomData($uuid, $data) {
        return $this->updateCustomDataAsync($uuid, $data)->wait();
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