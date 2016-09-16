<?php

namespace Ionic\Push\Models;

use Ionic\Users\Models\User;

/**
 * Class Targets
 * @method void addFacebookID(string $id)
 * @method void addGoogleID(string $id)
 * @method void addLinkedInID(string $id)
 * @method void addInstagramID(string $id)
 * @method void addFacebookIDs(array $ids)
 * @method void addGoogleIDs(array $ids)
 * @method void addLinkedInIDs(array $ids)
 * @method void addInstagramIDs(array $ids)
 */
class Targets {
    private $send_to_all = false;

    private $tokens = [];
    function addTokens($tokens) {
        $this->tokens = array_merge($this->tokens, $tokens);
    }
    function addToken($token) {
        array_push($this->tokens, $token);
    }

    private $user_ids = [];
    function addUserIDs($user_ids) {
        $this->user_ids = array_merge($this->user_ids, $user_ids);
    }
    function addUserID($user_id) {
        array_push($this->user_ids, $user_id);
    }
    function addUser(User $user) {
        array_push($this->user_ids, $user->uuid);
    }

    private $emails = [];
    function addEmails($emails) {
        $this->emails = array_merge($this->emails, $emails);
    }
    function addEmail($email) {
        array_push($this->emails, $email);
    }

    private $external_ids = [];
    private $facebook_ids = [];
    private $google_ids = [];
    private $twitter_ids = [];
    private $linkedin_ids = [];
    private $github_ids = [];

    function addSocialID($type, $id) {
        array_push($this->{$type.'_ids'}, $id);
    }
    function addSocialIDs($type, $ids) {
        $this->{$type.'_ids'} = array_merge($this->{$type.'_ids'}, $ids);
    }

    function __call($name, $arguments) {
        if (substr($name, 0, 3) == "add") {
            if (substr($name, -3) == "IDs"){
                $type = strtolower(substr($name, 0, -3));
                $this->addSocialIDs($type, $arguments[0]);
            } else {
                $type = strtolower(substr($name, 0, -2));
                $this->addSocialID($type, $arguments[0]);
            }
        }
    }

    static function createSendToAll() {
        $targets = new Targets();
        $targets->send_to_all = true;
        return $targets;
    }

    function targets() {
        if ($this->send_to_all) {
            return ["send_to_all" => true];
        }

        $targets = [];
        if (!empty($this->tokens)) {
            $targets["tokens"] = $this->tokens;
        }
        if (!empty($this->user_ids)) {
            $targets["user_ids"] = $this->user_ids;
        }
        if (!empty($this->emails)) {
            $targets["emails"] = $this->emails;
        }
        if (!empty($this->external_ids)) {
            $targets["external_ids"] = $this->external_ids;
        }
        if (!empty($this->facebook_ids)) {
            $targets["facebook_ids"] = $this->facebook_ids;
        }
        if (!empty($this->google_ids)) {
            $targets["google_ids"] = $this->google_ids;
        }
        if (!empty($this->twitter_ids)) {
            $targets["twitter_ids"] = $this->twitter_ids;
        }
        if (!empty($this->linkedin_ids)) {
            $targets["linkedin_ids"] = $this->linkedin_ids;
        }
        if (!empty($this->github_ids)) {
            $targets["github_ids"] = $this->github_ids;
        }
        return $targets;
    }
}