<?php
/**
 * Copyright 2017 SARE S.A
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 *
 */

namespace SAREhub\Microt\Util;

use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

class JsonResponseTest extends TestCase {
	
	private $orginal;
	/**
	 * @var JsonResponse
	 */
	private $resp;
	
	protected function setUp() {
		$this->orginal = new Response();
		$this->resp = JsonResponse::wrap($this->orginal);
	}
	
	public function testOk() {
		$this->assertResponse(200, ['data'], $this->resp->ok(['data']));
	}
	
	public function testCreated() {
		$this->assertResponse(201, ['data'], $this->resp->created(['data']));
	}
	
	public function testCreateErrorBody() {
		$message = 'test_message';
		$details = ['test_details'];
		$expectedBody = ['message' => $message, 'details' => $details];
		$this->assertEquals($expectedBody, JsonResponse::createErrorBody($message, $details));
	}
	
	public function testBadRequest() {
		$message = 'test_message';
		$details = ['detail'];
		$expectedBody = JsonResponse::createErrorBody($message, $details);
		$this->assertResponse(400, $expectedBody, $this->resp->badRequest($message, $details));
	}
	
	public function testNotFound() {
		$message = 'test_message';
		$details = ['detail'];
		$expectedBody = JsonResponse::createErrorBody($message, $details);
		$this->assertResponse(404, $expectedBody, $this->resp->notFound($message, $details));
	}
	
	public function testInternalServerError() {
		$message = 'test_message';
		$details = ['detail'];
		$expectedBody = JsonResponse::createErrorBody($message, $details);
		$this->assertResponse(500, $expectedBody, $this->resp->internalServerError($message, $details));
	}
	
	private function assertResponse($expectedCode, array $expectedBody, Response $response) {
		$this->assertEquals($expectedCode, $response->getStatusCode(), 'response status code');
		$this->assertJsonStringEqualsJsonString(json_encode($expectedBody, JSON_PRETTY_PRINT), (string)$response->getBody(), 'response body');
	}
}
