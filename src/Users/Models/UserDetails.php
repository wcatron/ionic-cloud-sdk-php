<?php

namespace Ionic\Users\Models;
use Ionic\Helpers\Changable;

/**
 * Class UserDetails
 * @package Ionic\Users\Models
 * @property string name
 * @property string image
 */
class UserDetails implements Changable {
    private $image;
    private $name;

    function __construct($array = []) {
        if (!empty($array)) {
            $this->image = $array['image'];
            $this->name = $array['name'];
        }
    }

    /**
     * @return static
     */
    static function createFromArray($array) {
        /** @var UserDetails[] $detailTypes */
        $detailTypes = [
            FacebookUserDetails::class,
            GithubUserDetails::class,
            GoogleUserDetails::class,
            InstagramUserDetails::class,
            LinkedInUserDetails::class,
            TwitterUserDetails::class,
            EmailPasswordUserDetails::class,
            CustomUserDetails::class,
            UserDetails::class
        ];
        foreach ($detailTypes as $class) {
            if ($class::isType($array)) {
                return new $class($array);
            }
        }
    }

    /**
     * Is type of user that subclass class supports.
     * @param mixed $array
     * @return bool
     */
    static function isType($array) {
        return true;
    }

    protected $changes = [];

    function changes() {
        return $this->changes;
    }

    function __set($name, $value) {
        if ($this->$name != $value) {
            $this->$name = $value;
            $this->changes[$name] = $value;
        }
    }

    function __get($name) {
        return $this->$name;
    }
}