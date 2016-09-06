<?php

namespace Ionic\Interfaces;

use Ionic\Helpers\CustomAuthenticationException;

interface CustomAuthenticator {
    /**
     * @param array $data of authentication data. (i.e. ["username" => ..., "password" => ...]
     * @return string User ID
     * @throws CustomAuthenticationException
     */
    function authenticate($data);
}