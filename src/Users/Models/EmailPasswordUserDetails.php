<?php
/**
 * Created by PhpStorm.
 * User: westoncatron
 * Date: 9/12/16
 * Time: 2:22 PM
 */

namespace Ionic\Users\Models;


class EmailPasswordUserDetails extends UserDetails {
    var $username;
    var $email;
    var $password;

    function __construct($array = null) {
        if ($array) {
            $this->email = $array['email'];
            $this->username = $array['username'];
        }
        parent::__construct($array);
    }
}