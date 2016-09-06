<?php

namespace Ionic\Helpers;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Ionic\Interfaces\CustomAuthenticator;

class CustomAuthentication {
    private $secret;
    private $authenticator;

    function __construct($secret, CustomAuthenticator $authenticator) {
        $this->secret = $secret;
        $this->authenticator = $authenticator;
    }

    /**
     * @param $token        string Token sent in GET request.
     * @param $redirect_uri string Redirect URI sent in GET request.
     * @param $state        string State sent in GET request.
     * @return string
     * @throws CustomAuthenticationException    Authentication failed.
     * @throws \UnexpectedValueException        Provided JWT was invalid
     * @throws SignatureInvalidException        Provided JWT was invalid because the signature verification failed
     * @throws BeforeValidException             Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws BeforeValidException             Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws ExpiredException                 Provided JWT has since expired, as defined by the 'exp' claim
     *
     */
    function process($token, $redirect_uri, $state) {
        $request = JWT::decode($token, $this->secret, [ "HS256" ]);

        $user_id = $this->authenticator->authenticate((array)$request->data);

        $token = JWT::encode([ 'user_id' => $user_id ], $this->secret);

        return $redirect_uri . '&' . http_build_query(
            [
                'token' => $token,
                'state' => $state,
            ]);
    }
}