<?php

class FunctionsTest extends PHPUnit_Framework_TestCase {
    function testRequired() {
        $config = [
            'requiredVar' => 'ABC'
        ];
        $requiredVar = required('requiredVar', $config);
        $this->assertTrue($requiredVar == $config['requiredVar']);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No requiredVar found.
     */
    function testRequiredFail() {
        $config = [
        ];
        $requiredVar = required('requiredVar', $config, "No {param} found.", InvalidArgumentException::class);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage requiredVar is required in configs.
     */
    function testRequiredFailDefault() {
        $config = [
        ];
        $requiredVar = required('requiredVar', $config);
    }
}