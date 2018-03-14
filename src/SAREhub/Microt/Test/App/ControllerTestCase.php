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

namespace SAREhub\Microt\Test\App;

use PHPUnit\Framework\TestCase;
use SAREhub\Microt\App\BasicController;
use SAREhub\Microt\App\Controller;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class ControllerTestCase extends TestCase {

    protected function callAction(Controller $controller, string $action, Request $request, Response $response = null): Response
    {
        return $controller->{$action . 'Action'}($request, $response ?? HttpHelper::response());
	}
	
	protected function assertResponseCode(int $expected, Response $response) {
		$this->assertEquals($expected, $response->getStatusCode(), 'response status code');
	}
	
	protected function assertJsonResponse(int $expectedCode, $expectedBody, Response $response) {
		$this->assertResponseCode($expectedCode, $response);
		$this->assertJsonStringEqualsJsonString(json_encode($expectedBody, JSON_PRETTY_PRINT), (string)$response->getBody(), 'json response body');
	}
	
	protected function assertErrorJsonResponse(int $expectedCode, string $expectedMessage, $expectedDetails, Response $response) {
		$this->assertJsonResponse($expectedCode, JsonResponse::createErrorBody($expectedMessage, $expectedDetails), $response);
	}
	
}