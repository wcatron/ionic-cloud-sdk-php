<?php

namespace Ionic\Push\Models;

/**
 * Class Notification
 * @package Ionic\Push\Models
 *
 */
class Notification extends NotificationConfig {
    public $app_id;
    public $created;
    public $state;
    public $status;

    /** @var iOSNotificationConfig  */
    public $ios;
    /** @var AndroidNotificationConfig  */
    public $android;

    function __construct($array = []) {
        $this->ios = new iOSNotificationConfig();
        $this->android = new AndroidNotificationConfig();
        if (!empty($array)) {
            // Map array to values.
        }
    }

    function configs() {
        return array_merge(parent::values(), [
            'ios' => $this->ios->values(),
            'android' => $this->android->values()
        ]);
    }
    function values() {
        return [
            "configs" => $this->configs(),
            "app_id" => $this->app_id,
            "created" => $this->created,
            "state" => $this->state,
            "status" => $this->status
        ];
    }
}