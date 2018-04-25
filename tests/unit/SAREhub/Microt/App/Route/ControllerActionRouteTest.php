<?php


namespace SAREhub\Microt\App\Route;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\App\Controller\ControllerActionRoute;
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

    public function testInjectToWhenHasMiddleware()
    {
        $middleware = function () {
        };

        $actionRoute = ControllerActionRoute::route()
            ->httpMethod('m')
            ->pattern('p')
            ->controller('c')
            ->action('b')
            ->addMiddleware($middleware);
        $app = \Mockery::mock(App::class);
        $route = \Mockery::mock(RouteInterface::class);

        $app->expects('map')->withArgs([['m'], 'p', ["c", "bAction"]])->andReturn($route);
        $route->expects("add")->withArgs([$middleware]);

        $actionRoute->injectTo($app);
    }

    public function testInjectToWhenHasNotMiddleware()
    {
        $actionRoute = ControllerActionRoute::route()
            ->httpMethod('m')
            ->pattern('p')
            ->controller('c')
            ->action('b');
        $app = \Mockery::mock(App::class);
        $route = \Mockery::mock(RouteInterface::class);

        $app->expects('map')->withArgs([['m'], 'p', ["c", "bAction"]])->andReturn($route);
        $route->expects("add")->never();

        $actionRoute->injectTo($app);
    }

    private function assertRoute(string $expectedMethod, string $expectedPattern, string $expectedAction, ControllerActionRoute $r)
    {
        $this->assertEquals($expectedMethod, $r->getHttpMethod(), 'route http method');
        $this->assertEquals($expectedPattern, $r->getPattern(), 'route pattern');
        $this->assertEquals($expectedAction, $r->getAction(), 'route action');
    }


}
