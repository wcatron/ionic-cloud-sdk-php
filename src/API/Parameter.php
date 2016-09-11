<?php

namespace Ionic\API;

class Parameter {
    /** @var string */
    var $name;
    /** @var bool */
    var $required = false;
    /** @var string Part of request parameter is in. */
    var $in;
    var $type;

    function __construct($definition) {
        if (isset($definition['name'])) {
            $this->name = $definition['name'];
        }
        if (isset($definition['required'])) {
            $this->required = $definition['required'];
        }
        if (isset($definition['in'])) {
            $this->in = $definition['in'];
        }
        if (isset($definition['type'])) {
            $this->type = $definition['type'];
        }
    }

    static function parseDefinitionToParameters($definition, &$parameters) {
        if (isset($definition['schema'])) {
            self::parseSchemaToParameters($definition['schema'], $parameters);
        } else {
            $parameter = new Parameter($definition);
            array_push($parameters, $parameter);
        }
    }

    static private function parseSchemaToParameters($schema, &$parameters) {
        if ($schema['type'] == "object") {
            foreach ($schema['properties'] as $param => $property_info) {
                $parameter = new Parameter($property_info);
                $parameter->name = $param;
                $parameter->in = $schema['in'];
                array_push($parameters, $parameter);
            }
        }
    }



}