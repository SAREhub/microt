<?php


namespace SAREhub\Microt\Test;


use Slim\Http\Request;
use Slim\Http\Response;

class CallableMock {
	public function __invoke(Request $request, Response $response) {
	
	}
	
	public static function create() {
		return \Mockery::mock(self::class);
	}
}