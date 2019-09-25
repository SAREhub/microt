<?php

namespace SAREhub\Microt\App\Controller;

use PHPUnit\Framework\TestCase;


class ExampleControllerRoutesProvider extends ControllerRoutesProvider
{

    public $actionRoute;

    public function __construct(array $groupMiddlewares = [], $actionMiddlewares = [])
    {
        parent::__construct($groupMiddlewares, $actionMiddlewares);
        $this->actionRoute = ControllerActionRoute::get("test", "test");
    }


    protected function getBaseUri(): string
    {
        return "test_base_uri";
    }

    protected function getControllerClass(): string
    {
        return "test_controller_class";
    }

    protected function injectRoutes(ControllerActionRoutes $routes)
    {
        $routes->addRoute($this->actionRoute);
    }
}

class ControllerRoutesProviderTest extends TestCase
{

    public function testGetThenRoutesHasBaseUri()
    {
        $provider = new ExampleControllerRoutesProvider();

        $routes = $provider->get();

        $this->assertEquals("test_base_uri", $routes->getBaseUri());
    }

    public function testGetThenRoutesHasControllerClass()
    {
        $middleware = function () {
        };
        $provider = new ExampleControllerRoutesProvider([$middleware]);

        $routes = $provider->get();

        $this->assertEquals("test_controller_class", $routes->getController());
    }

    public function testGetThenRoutesHasMiddlewares()
    {
        $middleware = $this->createMiddleware();
        $provider = new ExampleControllerRoutesProvider([$middleware]);

        $routes = $provider->get();

        $this->assertEquals([$middleware], $routes->getMiddlewares());
    }

    public function testGetThenRoutesHasActionRoutes()
    {
        $provider = new ExampleControllerRoutesProvider();

        $routes = $provider->get();

        $this->assertSame($provider->actionRoute, $routes->getRoutes()[0]);
    }

    public function testGetThenActionRouteHasMiddlewares()
    {
        $middleware = $this->createMiddleware();
        $provider = new ExampleControllerRoutesProvider([], [
            "test" => [$middleware]
        ]);

        $routes = $provider->get();

        $actionRoute = $routes->getRoutes()[0];
        $actionRouteMiddlewares = $actionRoute->getMiddlewares();
        $this->assertCount(1, $actionRouteMiddlewares);
        $this->assertSame($middleware, $actionRouteMiddlewares[0]);
    }

    private function createMiddleware(): callable
    {
        return function () {

        };
    }
}
