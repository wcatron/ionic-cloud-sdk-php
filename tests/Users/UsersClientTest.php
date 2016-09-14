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
        $users = [ ];
        while ($pagination->isEnd() == false) {
            $users = array_merge($users, $client->getUsers($pagination));
        }

        $this->assertInstanceOf(Ionic\Users\Models\User::class, $users[0]);
        $this->assertEquals('Sample Name', $users[0]->details->name);
        $this->assertEquals(5, count($users));
    }

    function testCreateUser() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.create.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);
        $new_user = User::withEmailPassword(time() . "@email.com", "ABC");
        $new_user = $client->createUser($new_user);
        $this->assertTrue(isset($new_user->uuid));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage User details must be of type EmailPasswordUserDetails for creating a user. All other types are created when the user logins in the first time.
     */
    function testCreateUserFail() {
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponses([], UsersClient::class);
        $user = new User();
        $user->details->name = "Full name";
        $client->createUser($user);
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
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponses(
            [
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.get.json')),
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.update.json'))
            ], UsersClient::class);
        $user = $client->getUser('A');
        $user->details->name = "New Name";
        $user->setChanged("name");
        $user = $client->updateUser($user);
        $this->assertEquals("New Name", $user->details->name);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage No changes were made to the user. If you make a change you have to record it by calling setChanged() on the object.
     */
    function testUpdateUserNoChange() {
        $client = $this->getTestClientWithResponses(
            [
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.get.json')),
                new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.update.json'))
            ], UsersClient::class);
        $user = $client->getUser('A');
        $user->details->name = "New Name";
        $user = $client->updateUser($user);
        $this->assertEquals("New Name", $user->details->name);
    }

    function testDeleteUser() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.delete.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);
        $client->deleteUser('A');
    }

    function testGetCustomData() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.get.custom.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);
        $data = $client->getCustomData('A');
        $this->assertEquals([
                                "key" => "value",
                                "foo" => [
                                    "fee" => "fi",
                                    "fo"  => "fum"
                                ]
                            ], $data);
    }

    function testSetCustomData() {
        $response = new Response(200, [ ], file_get_contents(__DIR__ . '/../responses/user.set.custom.json'));
        /** @var UsersClient $client */
        $client = $this->getTestClientWithResponse($response, UsersClient::class);
        $data = $client->setCustomData('A', [
            "key" => "new_value",
            "foo" => [
                "fee" => "jack"
            ]
        ]);
        $this->assertEquals("jack", $data['foo']['fee']);
    }

}
