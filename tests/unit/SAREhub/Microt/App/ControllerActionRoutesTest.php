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

class ControllerActionRoutesTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var ControllerActionRoutes
     */
    private $routes;

    protected function setUp()
    {
        $this->routes = ControllerActionRoutes::create('base', 'c');
    }


    /**
     * @param string $httpMethod
     * @dataProvider httpMethodsProvider
     */
    public function testCreatedRoute(string $httpMethod)
    {
        $expectedPattern = "p";
        $expectedAction = "a";
        $route = $this->routes->$httpMethod($expectedPattern, $expectedAction);
        $this->assertRoute(strtoupper($httpMethod), $this->routes->getBaseUri() . $expectedPattern, $expectedAction, $route);
        $this->assertRouteController('c', $route);
        $this->assertSame([$route], $this->routes->getRoutes());
    }

    public function httpMethodsProvider()
    {
        $methods = ["post", "get", "put", "patch", "delete"];

        $datasets = [];
        foreach ($methods as $method) {
            $datasets[strtoupper($method)] = [$method];
        }

        return $datasets;
    }

    public function testAddRoute()
    {
        $r = ControllerActionRoute::route()->pattern('/p');
        $this->routes->addRoute($r);
        $this->assertRouteController('c', $r);
        $this->assertRoutePattern('base/p', $r);
        $this->assertSame([$r], $this->routes->getRoutes());
    }

    public function testInjectTo()
    {
        $app = \Mockery::mock(App::class);
        $route = \Mockery::mock(ControllerActionRoute::class);
        $route->shouldIgnoreMissing($route);
        $route->shouldReceive('injectTo')->with($app)->once();
        $this->routes->addRoute($route);
        $this->routes->injectTo($app);
    }

    private function assertRoute(string $expectedMethod, string $expectedPattern, string $expectedAction, ControllerActionRoute $r)
    {
        $this->assertRouteHttpMethod($expectedMethod, $r);
        $this->assertRoutePattern($expectedPattern, $r);
        $this->assertRouteAction($expectedAction, $r);
    }

    private function assertRouteHttpMethod(string $expected, ControllerActionRoute $route)
    {
        $this->assertEquals($expected, $route->getHttpMethod(), 'route http method');
    }

    private function assertRoutePattern(string $expected, ControllerActionRoute $route)
    {
        $this->assertEquals($expected, $route->getPattern(), 'route pattern');
    }

    private function assertRouteAction(string $expected, ControllerActionRoute $route)
    {
        $this->assertEquals($expected, $route->getAction(), 'route action');
    }

    private function assertRouteController(string $expected, ControllerActionRoute $route)
    {
        $this->assertEquals($expected, $route->getController(), 'route controller');
    }
}
