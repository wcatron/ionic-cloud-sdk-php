<?php

namespace Ionic\Users\Models;

class LinkedInUserDetails extends UserDetails {
    var $linkedin_id;

    function __construct(array $array) {
        $this->linkedin_id = $array['linkedin_id'];
        parent::__construct($array);
    }

    static function isType($array) {
        return isset($array['linkedin_id']);
    }
}