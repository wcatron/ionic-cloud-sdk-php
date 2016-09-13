<?php

namespace Ionic\Users;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\Promise;
use Ionic\Client;
use Ionic\Helpers\Pagination;
use Ionic\Users\Models\EmailPasswordUserDetails;
use Ionic\Users\Models\User;

/**
 * Class UsersClient
 * @package Ionic\Users
 * @method User createUser(User $user)
 * @method User getUser(string $uuid)
 * @method User updateUser(User $user)
 * @method void deleteUser(string $uuid)
 * @method mixed getCustomData(string $uuid)
 * @method mixed setCustomData(string $uuid, mixed $data)
 */
class UsersClient extends Client {

    function getDefaults($config = [ ]) {
        return parent::getDefaults(
            array_replace_recursive(
                [
                    'route_parser' => [
                        'configs' => [
                            'version' => '2.0.0-beta.0',
                            'client'  => 'users'
                        ]
                    ]
                ], $config)
        );
    }

    /**
     * @param $pagination Pagination
     * @return User[]
     *                    Note: Pass by reference requires this function be implemented fully vs called via __call()
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
        return $this->getCommand('getUsers', [ 'page_size' => $pagination->pageSize,
                                               'page'      => $pagination->page ])->resolve()->then(function ($results) use (&$pagination) {
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
        /** @var EmailPasswordUserDetails $details */
        $details = $user->details;
        if (!($details instanceof EmailPasswordUserDetails)) {
            throw new \InvalidArgumentException("User details must be of type EmailPasswordUserDetails for creating a user. All other types are created when the user logins in the first time.");
        }
        $params = [
            "name" => $details->name,
            "app_id" => $this->app_id,
            "username" => $details->username,
            "password" => $details->password,
            "email" => $details->email,
            "custom" => $user->custom,
            "image" => $user->details->image
        ];
        return $this->getCommand('createUser', $params)->resolve();
    }

    /**
     * @param $uuid
     * @return Promise
     */
    function getUserAsync($uuid) {
        return $this->getCommand('getUser', [ 'user_uuid' => $uuid ])->resolve();
    }

    /**
     * @param User $user
     * @return Promise
     */
    function updateUserAsync($user) {
        $changed = $user->getChangedValues();
        if (count($changed) == 0) {
            throw new \Exception("No changes were made to the user. If you make a change you have to record it by calling setChanged() on the object.");
        }
        return $this->getCommand('updateUser', array_merge(['user_uuid' => $user->uuid], $changed))->resolve();
    }

    /**
     * @param $uuid
     * @return Promise
     */
    function deleteUserAsync($uuid) {
        return $this->getCommand('deleteUser', [ 'user_uuid' => $uuid ])->resolve();
    }

    /**
     * @param string $uuid
     * @return Promise
     */
    function getCustomDataAsync($uuid) {
        return $this->getCommand('getCustomData', [ 'user_uuid' => $uuid ])->resolve();
    }

    /**
     * @param string $uuid
     * @param mixed  $data
     * @return Promise
     */
    function setCustomDataAsync($uuid, $data) {
        return $this->getCommand('setCustomData', [ 'user_uuid' => $uuid, 'data' => $data ])->resolve();
    }

}