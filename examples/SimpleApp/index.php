<?php

use DI\ContainerBuilder;
use SAREhub\Microt\App\AppBootstrap;
use SAREhub\Microt\App\AppRunOptions;
use SAREhub\Microt\App\BasicContainerConfigurator;
use SAREhub\Microt\App\ChainContainerConfigurator;
use SAREhub\Microt\App\ContainerConfigurator;
use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . "/bootstrap.php";


class SimpleContainerConfigurator implements ContainerConfigurator
{

    public function configure(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            "messageFormat" => "Hello %s"
        ]);
    }
}

class SimpleMiddlewareInjector implements MiddlewareInjector
{
    public function injectTo(App $app)
    {
        $app->get("/hello/{name}", function (Request $request, Response $response) use ($app) {
            return sprintf($app->getContainer()->get("messageFormat"), $request->getAttribute("name"));
        });
    }
}

AppBootstrap::create(new AppRunOptions(new ChainContainerConfigurator([
    new BasicContainerConfigurator(),
    new SimpleContainerConfigurator()
]), new SimpleMiddlewareInjector()))->run();