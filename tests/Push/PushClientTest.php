<?php

use Ionic\Push\Models\Notification;
use Ionic\Push\Models\Targets;
use Ionic\Push\PushClient;

class PushClientTest extends PHPUnit_Framework_TestCase {

    function testCreateClient() {
        $this->markTestSkipped("For demonstration purposes only.");
        $client = new PushClient(
            [
                'api_token' => 'XXXXX',
                'app_id'    => 'XXXXX',
                'profile_tag' => "PROFILE_TAG"
            ]);

        $targets = new Targets();
        $targets->addTokens(["TOKENA", "TOKENB"]);

        $notification = new Notification();
        $notification->title("Notification Title")->message("Notification message...");

        $notification = $client->push($notification, $targets);

        $this->assertTrue($notification->state == "pending");

        $date = new DateTime('now');
        $date->add(DateInterval::createFromDateString('1 day'));
        $notification = $client->push($notification, Targets::createSendToAll(), $date);

        $this->assertTrue($notification->state == "scheduled");
    }
}