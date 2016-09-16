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
        if (!empty($array)) {
            $this->app_id = $array['app_id'];
            $this->created = new \DateTime($array['created']);
            $this->state = $array['state'];
            $this->state = $array['status'];
    
            $this->ios = new iOSNotificationConfig($array['configs']['ios']);
            $this->android = new iOSNotificationConfig($array['configs']['android']);
            
            parent::__construct($array['configs']);
        } else {
            $this->ios = new iOSNotificationConfig();
            $this->android = new AndroidNotificationConfig();
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