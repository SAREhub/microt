<?php

use DI\ContainerBuilder;
use SAREhub\Microt\App\AppBootstrap;
use SAREhub\Microt\App\AppRunOptions;
use SAREhub\Microt\App\BasicContainerConfigurator;
use SAREhub\Microt\App\ChainContainerConfigurator;
use SAREhub\Microt\App\ContainerConfigurator;
use SAREhub\Microt\App\Middleware\AppMiddlewaresInjector;
use SAREhub\Microt\HealthCheck\HealthCheckCommand;
use SAREhub\Microt\HealthCheck\HealthCheckResult;
use SAREhub\Microt\HealthCheck\HealthCheckRoutesProvider;

require dirname(__DIR__) . "/bootstrap.php";


class SimpleHealthCheckCommand implements HealthCheckCommand
{

    public function perform(): HealthCheckResult
    {
        return HealthCheckResult::createPass(["database" => "ok"]);
    }
}

class HealthCheckContainerConfigurator implements ContainerConfigurator
{
    const APP_MIDDLEWARES = "app.middlewares";

    public function configure(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            HealthCheckCommand::class => \DI\create(SimpleHealthCheckCommand::class),
            HealthCheckRoutesProvider::class => \DI\create()->constructor("/my"),
            self::APP_MIDDLEWARES => [
                \DI\factory(HealthCheckRoutesProvider::class)
            ]
        ]);
    }
}

$appRunOptions = new AppRunOptions(new ChainContainerConfigurator([
    new BasicContainerConfigurator(),
    new HealthCheckContainerConfigurator()
]), new AppMiddlewaresInjector(HealthCheckContainerConfigurator::APP_MIDDLEWARES));
AppBootstrap::create($appRunOptions)->run();