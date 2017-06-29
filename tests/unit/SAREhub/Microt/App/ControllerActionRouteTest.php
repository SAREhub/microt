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
use Slim\Container;
use Slim\Interfaces\RouteInterface;

class ControllerActionRouteTest extends TestCase {
	
	use MockeryPHPUnitIntegration;
	
	public function testPOST() {
		$r = ControllerActionRoute::post('p', 'a');
		$this->assertRoute('POST', 'p', 'a', $r);
	}
	
	public function testGET() {
		$r = ControllerActionRoute::get('p', 'a');
		$this->assertRoute('GET', 'p', 'a', $r);
	}
	
	public function testPUTRoute() {
		$r = ControllerActionRoute::put('p', 'a');
		$this->assertRoute('PUT', 'p', 'a', $r);
	}
	
	public function testPATCH() {
		$r = ControllerActionRoute::patch('p', 'a');
		$this->assertRoute('PATCH', 'p', 'a', $r);
	}
	
	public function testDELETE() {
		$r = ControllerActionRoute::delete('p', 'a');
		$this->assertRoute('DELETE', 'p', 'a', $r);
	}
	
	public function testOPTIONS() {
		$r = ControllerActionRoute::options('p', 'a');
		$this->assertRoute('OPTIONS', 'p', 'a', $r);
	}
	
	public function testGetControllerActionString() {
		$r = ControllerActionRoute::route()->controller('c')->action('b');
		$this->assertEquals('c:bAction', $r->getControllerActionString());
	}
	
	public function testInjectTo() {
		$middlewareInjector = \Mockery::mock(RouteMiddlewareInjector::class);
		$r = ControllerActionRoute::route()
		  ->httpMethod('m')
		  ->pattern('p')
		  ->controller('c')
		  ->action('b')
		  ->middlewareInjector($middlewareInjector);
		
		$app = \Mockery::mock(App::class);
		$container = new Container();
		$app->shouldReceive('getContainer')->andReturn($container);
		$route = \Mockery::mock(RouteInterface::class);
		$app->shouldReceive('map')->with(['m'], 'p', 'c:bAction')->andReturn($route)->once();
		$middlewareInjector->shouldReceive('setContainer')->with($container)->once();
		$middlewareInjector->shouldReceive('injectTo')->with($route)->once();
		$r->injectTo($app);
	}
	
	private function assertRoute(string $expectedMethod, string $expectedPattern, string $expectedAction, ControllerActionRoute $r) {
		$this->assertEquals($expectedMethod, $r->getHttpMethod(), 'route http method');
		$this->assertEquals($expectedPattern, $r->getPattern(), 'route pattern');
		$this->assertEquals($expectedAction, $r->getAction(), 'route action');
	}
	
	
}
