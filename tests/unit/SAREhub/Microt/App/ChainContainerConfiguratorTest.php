<?php

namespace SAREhub\Microt\App;

use DI\ContainerBuilder;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ChainContainerConfiguratorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | ContainerBuilder
     */
    private $containerBuilder;

    /**
     * @var MockInterface | ContainerConfigurator
     */
    private $configurator;

    /**
     * @var ChainContainerConfigurator
     */
    private $chain;

    protected function setUp()
    {
        $this->containerBuilder = \Mockery::mock(ContainerBuilder::class);
        $this->configurator = \Mockery::mock(ContainerConfigurator::class);
        $this->chain = new ChainContainerConfigurator([$this->configurator]);
    }

    public function testConfigureThenCallCurrentAndNextConfigure()
    {
        $this->configurator->expects("configure")->withArgs([$this->containerBuilder]);
        $this->chain->configure($this->containerBuilder);
    }
}
