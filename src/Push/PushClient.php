<?php

namespace Ionic\Push;

use Ionic\Client;
use Ionic\Push\Models\Notification;
use Ionic\Push\Models\Targets;

/**
 * Class PushClient
 * @package Ionic\Push
 * @method Notification push(Notification $notification, Targets $targets)
 */
class PushClient extends Client {
    function getDefaults($config = [ ]) {
        return parent::getDefaults(
            array_replace_recursive(
                [
                    'route_parser' => [
                        'configs' => [
                            'version' => '2.0.0-beta.0',
                            'client'  => 'push'
                        ]
                    ]
                ], $config)
        );
    }

    function pushAsync(Notification $notification, Targets $targets) {
        $args = array_merge(["notification" => $notification->configs(), "profile" => $this->config['profile']], $targets->targets());
        $this->getCommand('createNotification', $args)->resolve();
    }
}