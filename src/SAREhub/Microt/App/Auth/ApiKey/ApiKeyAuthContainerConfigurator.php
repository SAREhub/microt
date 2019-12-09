<?php


namespace SAREhub\Microt\App\Auth\ApiKey;


use DI\ContainerBuilder;
use SAREhub\Microt\App\ContainerConfigurator;
use function DI\factory;

class ApiKeyAuthContainerConfigurator implements ContainerConfigurator
{
    public function configure(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            ApiKeyAuthMiddleware::class => factory(ApiKeyAuthMiddlewareProvider::class)
        ]);
    }
}
