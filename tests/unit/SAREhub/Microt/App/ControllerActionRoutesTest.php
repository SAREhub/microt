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

namespace SAREhub\Microt\App;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Slim\App;

class ControllerActionRoutesTest extends TestCase {
	
	use MockeryPHPUnitIntegration;
	
	/**
	 * @var ControllerActionRoutes
	 */
	private $routes;
	
	protected function setUp() {
		$this->routes = ControllerActionRoutes::create('base', 'c');
	}
	
	public function testPOST() {
		$r = $this->routes->post('/p', 'a');
		$this->assertRouteHttpMethod('POST', $r);
		$this->assertRoutePattern('base/p', $r);
		$this->assertRouteAction('a', $r);
		$this->assertSame([$r], $this->routes->getRoutes());
	}
	
	public function testGET() {
		$r = $this->routes->get('/p', 'a');
		$this->assertRouteHttpMethod('GET', $r);
		$this->assertRoutePattern('base/p', $r);
		$this->assertRouteAction('a', $r);
		$this->assertSame([$r], $this->routes->getRoutes());
	}
	
	public function testPUT() {
		$r = $this->routes->put('/p', 'a');
		$this->assertRouteHttpMethod('PUT', $r);
		$this->assertRoutePattern('base/p', $r);
		$this->assertRouteController('c', $r);
		$this->assertRouteAction('a', $r);
		$this->assertSame([$r], $this->routes->getRoutes());
	}
	
	public function testPATCH() {
		$r = $this->routes->patch('/p', 'a');
		$this->assertRouteHttpMethod('PATCH', $r);
		$this->assertRoutePattern('base/p', $r);
		$this->assertRouteController('c', $r);
		$this->assertRouteAction('a', $r);
		
		$this->assertSame([$r], $this->routes->getRoutes());
	}
	
	public function testDELETE() {
		$r = $this->routes->delete('/p', 'a');
		$this->assertRouteHttpMethod('DELETE', $r);
		$this->assertRoutePattern('base/p', $r);
		$this->assertRouteController('c', $r);
		$this->assertRouteAction('a', $r);
		
		$this->assertSame([$r], $this->routes->getRoutes());
	}
	
	public function testAddRoute() {
		$r = ControllerActionRoute::route()->pattern('/p');
		$this->routes->addRoute($r);
		$this->assertRouteController('c', $r);
		$this->assertRoutePattern('base/p', $r);
		$this->assertSame([$r], $this->routes->getRoutes());
	}
	
	public function testInjectTo() {
		$app = \Mockery::mock(App::class);
		$route = \Mockery::mock(ControllerActionRoute::class);
		$route->shouldIgnoreMissing($route);
		$route->shouldReceive('injectTo')->with($app)->once();
		$this->routes->addRoute($route);
		$this->routes->injectTo($app);
	}
	
	public function assertRouteHttpMethod(string $expected, ControllerActionRoute $route) {
		$this->assertEquals($expected, $route->getHttpMethod(), 'route http method');
	}
	
	public function assertRoutePattern(string $expected, ControllerActionRoute $route) {
		$this->assertEquals($expected, $route->getPattern(), 'route pattern');
	}
	
	public function assertRouteAction(string $expected, ControllerActionRoute $route) {
		$this->assertEquals($expected, $route->getAction(), 'route action');
	}
	
	public function assertRouteController(string $expected, ControllerActionRoute $route) {
		$this->assertEquals($expected, $route->getController(), 'route controller');
	}
}
