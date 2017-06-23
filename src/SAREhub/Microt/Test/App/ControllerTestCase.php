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
use SAREhub\Microt\Util\JsonResponse;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class ControllerTestCase extends TestCase {
	
	/**
	 * @var Container
	 */
	protected $container;
	
	/**
	 * @var BasicController
	 */
	protected $controller;
	
	protected function setUp() {
		$controllerClass = $this->getControllerClass();
		$this->container = new Container();
		$this->controller = new $controllerClass($this->container);
	}
	
	protected abstract function getControllerClass(): string;
	
	protected function injectDeps(array $deps) {
		foreach ($deps as $name => $dep) {
			$this->container[$name] = $dep;
		}
	}
	
	protected function getContainer(): Container {
		return $this->container;
	}
	
	protected function callAction(string $action, Request $request, Response $response = null): Response {
		return $this->controller->{$action.'Action'}($request, $response ?? HttpHelper::response());
	}
	
	protected function assertResponse(int $expectedCode, array $expectedBody, Response $response) {
		$this->assertEquals($expectedCode, $response->getStatusCode(), 'response status code');
		$this->assertJsonStringEqualsJsonString(json_encode($expectedBody, JSON_PRETTY_PRINT), (string)$response->getBody(), 'response body');
		$this->assertResponse($expectedCode, $expectedBody, $response);
	}
	
	protected function assertErrorResponse(int $expectedCode, string $expectedMessage, $expectedDetails, Response $response) {
		$this->assertResponse($expectedCode, JsonResponse::createErrorBody($expectedMessage, $expectedDetails), $response);
	}
	
}