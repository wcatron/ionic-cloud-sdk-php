<?php

namespace Ionic\Users\Models;

class InstagramUserDetails extends UserDetails {
    var $instagram_id;
    function __construct(array $array) {
        $this->instagram_id = $array['instagram_id'];
        parent::__construct($array);
    }

    static function isType($array) {
        return isset($array['instagram_id']);
    }
}