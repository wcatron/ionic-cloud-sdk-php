<?php

use GuzzleHttp\Psr7\Response;
use Ionic\Helpers\Pagination;
use Ionic\Test\TestingClient;
use Ionic\Users\Models\User;
use Ionic\Users\UsersClient;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/6/16
 * Time: 5:00 PM
 */
class UsersClientTest extends PHPUnit_Framework_TestCase {
    use TestingClient;

    function testGetUsers() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/users.list.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);

        $pagination = new Pagination();
        $users = $client->getUsers($pagination);

        $this->assertInstanceOf(Ionic\Users\Models\User::class, $users[0]);
        $this->assertEquals('Sample Name', $users[0]->details->name);
    }

    function testGetUsersPagination() {
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponses(
            [
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/users.list.a.json')),
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/users.list.b.json')),
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/users.list.c.json'))
            ], UsersClient::class);

        $pagination = new Pagination(0, 2);
        $users = [];
        while ($pagination->isEnd() == false) {
            $users = array_merge($users, $client->getUsers($pagination));
        }

        $this->assertInstanceOf(Ionic\Users\Models\User::class, $users[0]);
        $this->assertEquals('Sample Name', $users[0]->details->name);
        $this->assertEquals(5, count($users));
    }

    function testCreateUser() {
        // TODO: Create test create user.
        $this->markTestSkipped('Not yet implemented.');
    }

    function testGetUser() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.get.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);
        $user = $client->getUser('A');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('A', $user->uuid);
    }

    function testUpdateUser() {
        // TODO: Create test update user.
        $this->markTestSkipped('Not yet implemented.');
    }
    function testDeleteUser() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.delete.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);
        $client->deleteUser('A');
    }
    function testGetCustomData() {
        // TODO: Test get custom data.
        $this->markTestSkipped('Not yet implemented.');
    }

    function testUpdateCustomData() {
        // TODO: Test update custom data.
        $this->markTestSkipped('Not yet implemented.');
    }

}
