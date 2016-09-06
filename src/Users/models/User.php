<?php

namespace Ionic\Users\Models;

class User {
    /**
     * @var string
     */
    var $app_id;
    /**
     * @var \DateTime
     */
    var $created;
    /**
     * @var mixed
     */
    var $custom;
    /**
     * @var UserDetails
     */
    var $details;
    /**
     * @var SocialDetails[]
     */
    var $social;
    /**
     * @var string
     */
    var $uuid;
}