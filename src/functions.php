<?php

function required($param, $array, $message = "{param} is required in configs.", $class = InvalidArgumentException::class) {
    if (!empty($array[$param])) {
        return $array[$param];
    }
    throw new $class(str_replace("{param}", $param, $message));
}