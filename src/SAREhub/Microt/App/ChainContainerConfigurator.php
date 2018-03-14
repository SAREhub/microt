<?php


namespace SAREhub\Microt\App;


use DI\ContainerBuilder;

class ChainContainerConfigurator implements ContainerConfigurator
{
    /**
     * @var ContainerConfigurator[]
     */
    private $configurators;

    public function __construct(array $configurators)
    {
        $this->configurators = $configurators;
    }

    public function configure(ContainerBuilder $builder)
    {
        foreach ($this->configurators as $configurator) {
            $configurator->configure($builder);
        }
    }
}
