<?php

namespace SAREhub\Microt\App;


use DI\ContainerBuilder;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class BasicContainerConfiguratorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | ContainerBuilder
     */
    private $containerBuilder;

    /**
     * @var BasicContainerConfigurator
     */
    private $configurator;

    protected function setUp()
    {
        $this->containerBuilder = \Mockery::mock(ContainerBuilder::class)->shouldIgnoreMissing();
        $this->configurator = new BasicContainerConfigurator(["test"]);
    }

    public function testConfigureThenBuilderUseAutowiring()
    {
        $this->containerBuilder->expects("useAutowiring")->withArgs([true]);
        $this->configurator->configure($this->containerBuilder);
    }

    public function testConfigureThenBuilderUseAnnotations()
    {
        $this->containerBuilder->expects("useAnnotations")->withArgs([false]);
        $this->configurator->configure($this->containerBuilder);
    }

    public function testConfigureThenBuilderAddDefinitions()
    {
        $this->containerBuilder->expects("addDefinitions")->withArgs([["test"]]);
        $this->configurator->configure($this->containerBuilder);
    }
}
