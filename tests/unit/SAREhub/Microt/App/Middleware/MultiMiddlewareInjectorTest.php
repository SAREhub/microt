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

namespace SAREhub\Microt\App\Middleware;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\Test\CallableMock;
use Slim\App;

class MultiMiddlewareInjectorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | App
     */
    private $app;

    protected function setUp()
    {
        $this->app = \Mockery::mock(App::class);
    }

    public function testInjectToWhenIsInstanceOfInjector()
    {
        $injector = \Mockery::mock(MiddlewareInjector::class);
        $injector->expects('injectTo')->withArgs([$this->app]);
        $multiInjector = new MultiMiddlewareInjector([$injector]);
        $multiInjector->injectTo($this->app);
    }

    public function testInjectToWhenIsNotInstanceOfInjector()
    {
        $middleware = CallableMock::create();
        $this->app->expects("add")->withArgs([$middleware]);
        $multiInjector = new MultiMiddlewareInjector([$middleware]);
        $multiInjector->injectTo($this->app);
    }
}
