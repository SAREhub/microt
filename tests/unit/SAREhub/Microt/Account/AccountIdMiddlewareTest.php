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

namespace SAREhub\Microt\Account;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Test\CallableMock;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountIdMiddlewareTest extends TestCase {
	
	use MockeryPHPUnitIntegration;
	
	/**
	 * @var Container
	 */
	private $c;
	private $middleware;
	
	protected function setUp() {
		$this->c = new Container();
		$this->middleware = new AccountIdMiddleware($this->c);
	}
	
	public function testInvokeWhenRouteHasAccountIdArgument() {
		$this->invokeMiddleware($this->createRequestWithRoute([AccountIdMiddleware::ACCOUNT_ID_ENTRY => 1]));
		$this->assertEquals(1, $this->c[AccountIdMiddleware::ACCOUNT_ID_ENTRY]);
	}
	
	public function testInvokeWhenRouteWithoutAccoutIdArgument() {
		$this->invokeMiddleware($this->createRequestWithRoute());
		$this->assertFalse($this->c->has(AccountIdMiddleware::ACCOUNT_ID_ENTRY));
	}
	
	public function testInvokeWhenEmptyRouteThenEmptyAccountId() {
		$this->invokeMiddleware(HttpHelper::request());
		$this->assertFalse($this->c->has(AccountIdMiddleware::ACCOUNT_ID_ENTRY));
	}
	
	public function testInvokeThenCallNext() {
		$next = CallableMock::create();
		$request = HttpHelper::requestWithBody();
		$response = HttpHelper::response();
		$expectedResponse = HttpHelper::response();
		$next->shouldReceive('__invoke')->once()->with($request, $response)->andReturn($expectedResponse);
		$this->assertSame($expectedResponse, $this->invokeMiddleware($request, $response, $next));
	}
	
	private function invokeMiddleware(Request $request, Response $response = null, $next = null) {
		return ($this->middleware)($request, $response ?? HttpHelper::response(), $next ?? function () { });
	}
	
	/**
	 * @param array $arguments
	 * @return Request
	 */
	private function createRequestWithRoute(array $arguments = []) {
		return HttpHelper::request()->withAttribute('route', HttpHelper::routeWithArguments($arguments));
	}
}
