<?php
use Ionic\Helpers\Pagination;
use Ionic\Test\TestClient;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/6/16
 * Time: 5:00 PM
 */

class UserTest extends PHPUnit_Framework_TestCase {
    function testGetUsers() {
        $client = new TestClient([]);
        $client = new Ionic\Users\UsersClient($client);
        $pagination = new Pagination();
        $users = [];
        while (!$pagination->isEnd()) {
            array_merge($users, $client->getUsers($pagination));
        }
        $this->assertTrue(count($users) == 0);
    }
}
