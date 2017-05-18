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
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\MiddlewareInjector;
use Slim\App;
use Slim\Container;

class AppBootstrapTest extends TestCase {
	
	use MockeryPHPUnitIntegration;
	
	/**
	 * @var Mock
	 */
	private $app;
	
	private $container;
	
	/**
	 * @var Mock | ServiceProvider
	 */
	private $serviceProvider;
	
	/**
	 * @var Mock | MiddlewareInjector
	 */
	private $middlewareInjector;
	
	/**
	 * @var AppBootstrap
	 */
	private $bootstrap;
	
	protected function setUp() {
		$this->app = \Mockery::mock(App::class)->shouldIgnoreMissing();
		$this->container = new Container();
		$this->app->shouldReceive('getContainer')->andReturn($this->container);
		$this->serviceProvider = \Mockery::mock(ServiceProvider::class)->shouldIgnoreMissing();
		$this->middlewareInjector = \Mockery::mock(MiddlewareInjector::class)->shouldIgnoreMissing();
		$this->bootstrap = new AppBootstrap($this->app);
		$this->bootstrap->setServiceProvider($this->serviceProvider);
		$this->bootstrap->setMidlewareInjector($this->middlewareInjector);
	}
	
	public function testRunThenServiceProviderRegister() {
		$this->serviceProvider->shouldReceive('register')->with($this->container)->once();
		$this->bootstrap->run();
	}
	
	public function testRunThenMiddlewareInjector() {
		$this->middlewareInjector->shouldReceive('injectTo')->with($this->app)->once();
		$this->bootstrap->run();
	}
	
	public function testRunThenAppRun() {
		$this->app->shouldReceive('run')->once();
		$this->bootstrap->run();
	}
}
