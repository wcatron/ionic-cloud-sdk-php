<?php

namespace Ionic\Push\Models;

class Token {
    public $app_id;
    /** @var \DateTime */
    public $created;
    public $id;
    /** @var \DateTime */
    public $invalidated;
    public $token;
    public $type;
    public $valid;
    
    function __construct($array = null) {
        if ($array) {
            $this->app_id = $array['app_id'];
            $this->created = new \DateTime($array['created']);
            $this->id = $array['id'];
            $this->invalidated = new \DateTime($array['invalidated']);
            $this->token = $array['token'];
            $this->type = $array['type'];
            $this->valid = $array['valid'];
        }
    }
}