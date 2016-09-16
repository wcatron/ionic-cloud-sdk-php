<?php

namespace Ionic\Push\Models;

class Message {
    /** @var \DateTime */
    public $created;
    /** @var MessageError */
    public $error;
    public $notification_id;
    public $status;
    /** @var Token */
    public $token;
    public $user_id;
    public $uuid;
    
    function __construct($array = null) {
        if ($array) {
            $this->created = new \DateTime($array['created']);
            $this->error = new MessageError($array['error']);
            $this->notification_id = $array['notification_id'];
            $this->status = $array['status'];
            $this->token = new Token($array['token']);
            $this->user_id = $array['user_id'];
            $this->uuid = $array['uuid'];
        }
    }
}