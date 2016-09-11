<?php

/*
 * Run script with the following arguments:
 * 1: Path to swagger json file.
 * 2: Path to API json file.
 *
 * This script pulls the swagger parameter information into the api json file.
 * The API json file is responsible for naming the route (`getUsers`) and mapping the output (`..\Models\User`).
 * Swagger is responsible tracking the parameters.
 *
 * Sample:
 * php swagger_parser.php 2.0.0-beta.0/auth/swagger.json 2.0.0-beta.0/users.api.json
 *
 * TODO: This but better. Testing.
 */

$input_file = $argv[1];
$output_file = $argv[2];

function parseRefs($params, $refs) {
    $parsed = [];
    foreach ($params as $value) {
        if (is_array($value) && isset($value['$ref'])) {
            $param_name = explode('/', $value['$ref'])[2];
            // TODO: Confirm that when using $ref a parameter is not required.
            array_push($parsed, array_merge($refs[$param_name], [
                "required" => false
            ]));
        } else {
            array_push($parsed, $value);
        }
    }
    return $parsed;
}

$swagger = json_decode(file_get_contents($input_file), true);
$starting_output = json_decode(file_get_contents($output_file));
$output = clone $starting_output;

foreach($starting_output->routes as $route => $config) {
    $request_uri = $config->http->request_uri;
    $method = $config->http->method;
    if (isset($swagger['paths'][$request_uri][$method])) {
        $swagger_path = $swagger['paths'][$config->http->request_uri][$config->http->method];
    } else {
        $swagger_path = false;
    }

    if ($swagger_path) {
        $new_config = clone $config;
        $new_config->params = parseRefs($swagger_path['parameters'], $swagger['parameters']);
        if (property_exists($new_config, 'params_custom')) {
            $new_config->params = array_merge($new_config->params, $new_config->params_custom);
        }
        $output->routes->{$route} = $new_config;
    }
}

file_put_contents($output_file, json_encode($output,JSON_UNESCAPED_SLASHES));