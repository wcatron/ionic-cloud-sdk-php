<?php

namespace Ionic\Push\Models;


/**
 * Class iOSNotificationConfig
 * @package Ionic\Push\Models
 * @property string collapse_key
 * @property integer content_available
 * @property mixed data
 * @property boolean delay_while_idle
 * @property string icon
 * @property string image
 * @property string sound
 * @property string tag
 * @property integer time_to_live
 * @property mixed template_defaults
 * @method AndroidNotificationConfig collapse_key(string $collapse_key)
 * @method AndroidNotificationConfig content_available(integer $content_available)
 * @method AndroidNotificationConfig data(mixed $data)
 * @method AndroidNotificationConfig delay_while_idle(boolean $delay_while_idle)
 * @method AndroidNotificationConfig icon(integer $icon)
 * @method AndroidNotificationConfig image(string $image)
 * @method AndroidNotificationConfig sound(string $sound)
 * @method AndroidNotificationConfig tag(string $tag)
 * @method AndroidNotificationConfig time_to_live(integer $time_to_live)
 * @method AndroidNotificationConfig template_defaults(mixed $template_defaults)
 * @method AndroidNotificationConfig message(string $message)
 * @method AndroidNotificationConfig payload(string $payload)
 * @method AndroidNotificationConfig title(string $title)
 */
class AndroidNotificationConfig extends NotificationConfig {
    const Properties = ['message', 'payload', 'title', 'collapse_key', 'content_available', 'data', 'delay_while_idle', 'icon','image', 'sound', 'tag', 'template_defaults','time_to_live'];
}