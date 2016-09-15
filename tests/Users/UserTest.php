<?php

use Ionic\Users\Models\User;

class UserTest extends PHPUnit_Framework_TestCase {
    var $basic_user = [
        "app_id"  => "XXX",
        "details" => [
            "name"  => "Sample Name",
            "image" => "",
            "email" => "email@email.com"
        ],
        "created" => "2016-09-07T16:44:42Z",
        "uuid"    => "A"
    ];
    function testCreateUser() {
        $user = new User($this->basic_user);
        $this->assertEquals("XXX", $user->app_id);
        $this->assertEquals("2016-09-07T16:44:42+00:00", $user->created->format('c'));
        $this->assertEquals("A", $user->uuid);
    }

    function testMakeChanges() {
        $user = new User($this->basic_user);
        $this->assertTrue(empty($user->changes()));
        $user->details->name = "Sample Name";
        $this->assertTrue(empty($user->changes()), "Still no changes since name is the same.");
        $user->details->name = "New Name";
        $this->assertTrue(count($user->changes()) == 1);
    }

    function testChangeCustom() {
        $user = new User($this->basic_user);
        $custom = [
            "fee",
            "fi",
            "fo",
            "fum"
        ];
        $user->custom = $custom;
        $changes = $user->changes();
        $this->assertEquals($custom, $changes["custom"]);
    }

}