<?php

namespace SAREhub\Microt\App;


use DI\ContainerBuilder;
use Hamcrest\Matchers;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ServiceAppTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testConfigureContainer()
    {
        $configurator = \Mockery::mock(ContainerConfigurator::class);
        $configurator->expects("configure")->withArgs([Matchers::anInstanceOf(ContainerBuilder::class)]);
        new ServiceApp($configurator);
    }
}
