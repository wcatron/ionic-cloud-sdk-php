<?php

use Ionic\Push\Models\Targets;

class TargetsTest extends PHPUnit_Framework_TestCase {
    function testSendToAll() {
        $targets = Targets::createSendToAll();
        $this->assertTrue($targets->targets()['send_to_all']);
    }
}