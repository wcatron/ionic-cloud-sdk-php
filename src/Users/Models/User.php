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
    var $custom = [];
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
            $this->created = \DateTime::createFromFormat('c', $array['created']);
            $this->custom = empty($array['custom']) ? null : $array['custom'];
            $this->details = new UserDetails($array['details']);
            $this->uuid = $array['uuid'];
        } else {
            $this->details = new UserDetails();
        }
    }

    function getChangedValues() {
        $map = [
            "custom" => function () {
                return $this->custom;
            },
            "email" => function () {
                /** @var EmailPasswordUserDetails $details */
                $details = $this->details;
                return $details->email;
            },
            "image" => function () {
                return $this->details->image;
            },
            "name" => function () {
                return $this->details->name;
            },
            "password" => function () {
                /** @var EmailPasswordUserDetails $details */
                $details = $this->details;
                return $details->password;
            },
            "username" => function () {
                /** @var EmailPasswordUserDetails $details */
                $details = $this->details;
                return $details->username;
            }
        ];

        $values = [];

        foreach ($this->changed as $key) {
            if (!isset($map[$key])) {
                throw new \Exception("Can't update $key.");
            }
            $values[$key] = $map[$key]();
        }

        return $values;
    }

    static function withEmailPassword($email, $password) {
        $details = new EmailPasswordUserDetails();
        $details->email = $email;
        $details->password = $password;
        $user = new static();
        $user->details = $details;
        return $user;
    }

    private $changed = [];

    /**
     * @param $changed string The key for the changed value. Changeable values are name, email, image, password, custom, and username.
     * @throws \InvalidArgumentException Thrown if changed key is not valid.
     */
    function setChanged($changed) {
        if (in_array($changed, ["name", "email", "image", "password", "custom", "username"])) {
            array_push($this->changed, $changed);
            return;
        }
        throw new \InvalidArgumentException("Can't update $changed.");
    }
}