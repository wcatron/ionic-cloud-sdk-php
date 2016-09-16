<?php

namespace Ionic\Push\Models;

/**
 * Class NotificationConfig
 * @package Ionic\Push\Models
 * @property string message
 * @property mixed payload
 * @property string title
 * @method NotificationConfig message(string $message)
 * @method NotificationConfig payload(string $payload)
 * @method NotificationConfig title(string $title)
 */
class NotificationConfig {
    
    function __construct(array $array = null) {
        if ($array) {
            foreach ($array as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    
    const Properties = ['message', 'payload', 'title'];

    private $values = [];

    function values() {
        return $this->values;
    }

    function __get($name) {
        return $this->values[$name];
    }

    function __set($name, $value) {
        $this->values[$name] = $value;
    }

    function __call($name, $arguments) {
        if (in_array($name, static::Properties)) {
            $this->$name = $arguments[0];
        }
        return $this;
    }
}