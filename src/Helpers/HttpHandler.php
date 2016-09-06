<?php

namespace Ionic\Helpers;

class HttpHandler {
    const BASE_URL = "https://api.ionic.io/";

    static function call($method, $route, $params, $token) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request($method,
                                     static::BASE_URL . $route,
                                     [
                                         'query' => $params,
                                         'headers' => [ 'Authorization' => "Bearer $token",
                                                      'Content-Type'  => 'application/json' ] ]);
        return \GuzzleHttp\json_decode($response->getBody());
    }
}