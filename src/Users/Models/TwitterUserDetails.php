<?php

namespace Ionic\Users\Models;

class TwitterUserDetails extends UserDetails {
    var $twitter_id;

    function __construct(array $array) {
        $this->twitter_id = $array['twitter_id'];
        parent::__construct($array);
    }

    static function isType($array) {
        return isset($array['twitter_id']);
    }
}