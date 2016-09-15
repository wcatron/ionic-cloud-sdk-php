<?php

namespace Ionic\Users\Models;

use Ionic\Helpers\Changable;

/** @property mixed custom */
class User implements Changable {
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
    private $custom = [];
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
            $this->created = new \DateTime($array['created']);
            $this->custom = empty($array['custom']) ? null : $array['custom'];
            $this->details = UserDetails::createFromArray($array['details']);
            $this->uuid = $array['uuid'];
        } else {
            $this->details = new UserDetails();
        }
    }

    static function withEmailPassword($email, $password) {
        $details = new EmailPasswordUserDetails();
        $details->email = $email;
        $details->password = $password;
        $user = new static();
        $user->details = $details;
        return $user;
    }

    private $changes = [];
    function changes() {
        return array_merge($this->changes, $this->details->changes());
    }

    function __set($name, $value) {
        if ($name == "custom") {
            $this->custom = $value;
            $this->changes["custom"] = $value;
            return $this;
        }
        throw new \InvalidArgumentException("Can't change $name.");
    }

    function __get($name) {
        if ($name == "custom") {
            return $this->custom;
        }
        throw new \InvalidArgumentException("No such property $name.");
    }
}