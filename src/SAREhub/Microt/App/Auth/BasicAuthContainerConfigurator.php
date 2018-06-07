<?php


namespace SAREhub\Microt\App\Auth;


use DI\ContainerBuilder;
use SAREhub\Microt\App\ContainerConfigurator;
use function DI\autowire;
use function DI\create;
use function DI\factory;

class BasicAuthContainerConfigurator implements ContainerConfigurator
{
    public function configure(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            BasicAuthOptionsProvider::class => autowire(),
            BasicAuthMiddlewareInjector::class => create()->constructor(factory(BasicAuthOptionsProvider::class)),
        ]);
    }
}