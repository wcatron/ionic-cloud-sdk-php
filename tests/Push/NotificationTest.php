<?php

use Ionic\Push\Models\Notification;

class NotificationTest extends PHPUnit_Framework_TestCase {
    function testNotificationCreate() {
        $notification = new Notification();
        $notification->title = "Title of notification";

        $this->assertEquals("Title of notification", $notification->configs()['title']);

        $notification->title("New Title")->message("My message to the user.");

        $this->assertEquals("New Title", $notification->configs()['title']);
        $this->assertEquals("My message to the user.", $notification->configs()['message']);

        $notification->ios->title = "iOS only title.";
        $notification->ios->sound("sound.tiff")->badge(2)->message("iOS only message.");
        $notification->android->message("Android only message")->sound('android_beep.tiff');

        $this->assertEquals("sound.tiff", $notification->ios->sound);
    }
}