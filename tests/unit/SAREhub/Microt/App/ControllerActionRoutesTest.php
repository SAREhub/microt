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
	
	public function testAddRoute() {
		$route = ControllerActionRoute::route()->pattern('/p');
		$this->routes->addRoute($route);
		$this->assertEquals('c', $route->getControllerClass());
		$this->assertEquals('base/p', $route->getPattern());
		$this->assertSame([$route], $this->routes->getAll());
	}
	
	public function testInjectTo() {
		$app = \Mockery::mock(App::class);
		$route = \Mockery::mock(ControllerActionRoute::class);
		$route->shouldIgnoreMissing($route);
		$route->shouldReceive('injectTo')->with($app)->once();
		$this->routes->addRoute($route);
		$this->routes->injectTo($app);
	}
}
