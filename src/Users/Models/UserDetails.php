<?php

namespace Ionic\Users\Models;

/**
 * Class UserDetails
 * @package Ionic\Users\Models
 */
class UserDetails {
    var $image;
    var $name;

    function __construct($array) {
        if (!empty($array)) {
            $this->image = $array['image'];
            $this->name = $array['name'];
        }
    }
}