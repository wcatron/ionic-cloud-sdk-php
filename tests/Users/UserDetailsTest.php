<?php
use Ionic\Users\Models\CustomUserDetails;
use Ionic\Users\Models\FacebookUserDetails;
use Ionic\Users\Models\GithubUserDetails;
use Ionic\Users\Models\GoogleUserDetails;
use Ionic\Users\Models\InstagramUserDetails;
use Ionic\Users\Models\LinkedInUserDetails;
use Ionic\Users\Models\TwitterUserDetails;
use Ionic\Users\Models\User;
use Ionic\Users\Models\UserDetails;

/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/13/16
 * Time: 12:04 PM
 */
class UserDetailsTest extends PHPUnit_Framework_TestCase {
    function testCreateDetails() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "email" => "email@email.com"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertEquals("XXX", $user->app_id);
        $this->assertEquals("2016-09-07T16:44:42+00:00", $user->created->format('c'));
        $this->assertEquals("A", $user->uuid);
    }

    function testCreateFacebookUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "facebook_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof FacebookUserDetails, "Resulting details should be of type FacebookUserDetails.");
    }
    function testCreateGithubUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "github_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof GithubUserDetails, "Resulting details should be of type GithubUserDetails.");
    }
    function testCreateGoogleUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "google_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof GoogleUserDetails, "Resulting details should be of type GoogleUserDetails.");
    }
    function testCreateTwitterUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "twitter_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof TwitterUserDetails, "Resulting details should be of type TwitterUserDetails.");
    }
    function testCreateLinkedInUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "linkedin_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof LinkedInUserDetails, "Resulting details should be of type LinkedInUserDetails.");
    }
    function testCreateInstagramUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "instagram_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof InstagramUserDetails, "Resulting details should be of type InstagramUserDetails.");
    }
    function testCreateCustomUser() {
        $user = new User(
            [
                "app_id"  => "XXX",
                "details" => [
                    "name"  => "Sample Name",
                    "image" => "",
                    "external_id" => "sd9291"
                ],
                "created" => "2016-09-07T16:44:42Z",
                "uuid"    => "A"
            ]);
        $this->assertTrue($user->details instanceof CustomUserDetails, "Resulting details should be of type CustomUserDetails.");
    }

    function testCheck() {
        $this->assertFalse(new UserDetails() instanceof CustomUserDetails);
        $this->assertTrue(new CustomUserDetails(["external_id" => "AA", "image" => "", "name" => ""]) instanceof UserDetails);

    }
}
