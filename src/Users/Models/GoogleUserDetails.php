<?php

namespace Ionic\Users\Models;

class GoogleUserDetails extends UserDetails {
    var $google_id;

    function __construct(array $array) {
        $this->google_id = $array['google_id'];
        parent::__construct($array);
    }

    static function isType($array) {
        return isset($array['google_id']);
    }
}