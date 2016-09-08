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

    function __construct($array = null) {
        if ($array) {
            $this->app_id = $array['app_id'];
            $this->created = $array['created'];
            $this->custom = empty($array['custom']) ? null : $array['custom'];
            $this->details = new UserDetails($array['details']);
            $this->uuid = $array['uuid'];
        }
    }
}