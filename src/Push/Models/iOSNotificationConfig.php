<?php

namespace Ionic\Push\Models;

/**
 * Class iOSNotificationConfig
 * @package Ionic\Push\Models
 * @property integer badge
 * @property integer content_available
 * @property mixed data
 * @property string expire Date time formatted with 'c'. Would be nice to let this be set with a datetime and then get formatted for you.
 * @property integer priority
 * @property string sound
 * @property mixed template_defaults
 * @method iOSNotificationConfig badge(integer $badge)
 * @method iOSNotificationConfig content_available(integer $content_available)
 * @method iOSNotificationConfig data(mixed $data)
 * @method iOSNotificationConfig expire(string $expire)
 * @method iOSNotificationConfig priority(integer $priority)
 * @method iOSNotificationConfig sound(string $sound)
 * @method iOSNotificationConfig template_defaults(mixed $template_defaults)
 * @method iOSNotificationConfig message(string $message)
 * @method iOSNotificationConfig payload(string $payload)
 * @method iOSNotificationConfig title(string $title)
 */
class iOSNotificationConfig extends NotificationConfig {
    const Properties = ['message', 'payload', 'title', 'badge', 'content_available', 'data', 'expire', 'priority', 'sound', 'template_defaults'];
}