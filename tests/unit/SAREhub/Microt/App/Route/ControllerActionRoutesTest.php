<?php


namespace SAREhub\Microt\App\Route;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\App\Controller\ControllerActionRoute;
use SAREhub\Microt\App\Controller\ControllerActionRoutes;
use Slim\App;
use Slim\Interfaces\RouteGroupInterface;

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

    public function testInjectToThenAppGroup()
    {
        $app = \Mockery::mock(App::class);
        $app->expects("group")
            ->withArgs([$this->routes->getBaseUri(), \Mockery::any()])
            ->andReturn(\Mockery::mock(RouteGroupInterface::class));
        $this->routes->injectTo($app);
    }

    public function testInjectToThenActionRoutesInjectTo()
    {
        $app = new App();
        $route = \Mockery::mock(ControllerActionRoute::class);
        $route->shouldIgnoreMissing($route);
        $route->expects('injectTo')->withArgs([$app]);
        $this->routes->addRoute($route);
        $this->routes->injectTo($app);
    }

    public function testInjectToWhenHasMiddlewares()
    {
        $app = \Mockery::mock(App::class);
        $middleware = function () {
        };
        $this->routes->addMiddleware($middleware);

        $routeGroup = \Mockery::mock(RouteGroupInterface::class);
        $app->expects("group")->andReturn($routeGroup);

        $routeGroup->expects("add")->withArgs([$middleware]);

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
