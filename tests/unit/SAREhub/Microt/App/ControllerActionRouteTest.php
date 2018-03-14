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
use Slim\Interfaces\RouteInterface;

class ControllerActionRouteTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @param $httpMethod
     * @dataProvider httpMethodsProvider
     */
    public function testCreatedRoute(string $httpMethod)
    {
        $expectedPattern = "p";
        $expectedAction = "a";
        $route = ControllerActionRoute::$httpMethod($expectedPattern, $expectedAction);
        $this->assertRoute(strtoupper($httpMethod), $expectedPattern, $expectedAction, $route);
    }

    public function httpMethodsProvider()
    {
        $methods = ["post", "get", "put", "patch", "delete", "options"];

        $datasets = [];
        foreach ($methods as $method) {
            $datasets[strtoupper($method)] = [$method];
        }

        return $datasets;
    }

    public function testGetControllerActionMethodString()
    {
        $r = ControllerActionRoute::route()->action('b');
        $this->assertEquals('bAction', $r->getControllerActionMethodString());
    }

    public function testGetControllerActionCallable()
    {
        $r = ControllerActionRoute::route()->controller('c')->action('b');
        $this->assertEquals(['c', 'bAction'], $r->getControllerActionCallable());
    }


    public function testInjectTo()
    {
        $middlewareInjector = \Mockery::mock(RouteMiddlewareInjector::class);
        $actionRoute = ControllerActionRoute::route()
            ->httpMethod('m')
            ->pattern('p')
            ->controller('c')
            ->action('b')
            ->middlewareInjector($middlewareInjector);

        $app = \Mockery::mock(App::class);
        $route = \Mockery::mock(RouteInterface::class);

        $app->expects('map')->withArgs([['m'], 'p', ["c", "bAction"]])->andReturn($route);
        $middlewareInjector->expects('injectTo')->withArgs([$route]);

        $actionRoute->injectTo($app);
    }

    private function assertRoute(string $expectedMethod, string $expectedPattern, string $expectedAction, ControllerActionRoute $r)
    {
        $this->assertEquals($expectedMethod, $r->getHttpMethod(), 'route http method');
        $this->assertEquals($expectedPattern, $r->getPattern(), 'route pattern');
        $this->assertEquals($expectedAction, $r->getAction(), 'route action');
    }


}
