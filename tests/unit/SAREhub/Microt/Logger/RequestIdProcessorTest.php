<?php


namespace SAREhub\Microt\Logger;

use PHPUnit\Framework\TestCase;

class RequestIdProcessorTest extends TestCase {
	
	public function testInvoke() {
		$p = new RequestIdProcessor(1);
		$record = ['message' => 'test', 'extra' => []];
		$this->assertEquals(['message' => 'test', 'extra' => ['requestId' => 1]], $p($record));
	}
}
