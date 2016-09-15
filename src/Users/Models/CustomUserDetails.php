<?php

namespace Ionic\Users\Models;

class CustomUserDetails extends UserDetails {
    var $external_id;
    function __construct(array $array) {
        $this->external_id = $array['external_id'];
        parent::__construct($array);
    }

    static function isType($array) {
        return isset($array['external_id']);
    }
}