<?php

namespace SAREhub\Microt\App\Middleware;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\App\BasicContainerConfigurator;
use SAREhub\Microt\App\ServiceApp;


class AppMiddlewaresInjectorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testInjectTo()
    {
        $injector = \Mockery::mock(MiddlewareInjector::class);
        $containerInjector = new AppMiddlewaresInjector("middlewares");
        $app = new ServiceApp(new BasicContainerConfigurator([
            "middlewares" => [
                $injector
            ]
        ]));

        $injector->expects("injectTo")->withArgs([$app]);

        $containerInjector->injectTo($app);
    }
}
