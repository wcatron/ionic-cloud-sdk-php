<?php

namespace Ionic\Users\Models;

class FacebookUserDetails extends UserDetails {
    var $facebook_id;

    function __construct(array $array) {
        $this->facebook_id = $array['facebook_id'];
        parent::__construct($array);
    }

    static function isType($array) {
        return (isset($array['facebook_id']));
    }
}