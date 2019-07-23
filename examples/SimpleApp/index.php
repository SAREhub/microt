<?php
/**
 * Simple app + route with attribute
 * BROWSER: http://localhost:8080/hello/<your name>
 */
use DI\ContainerBuilder;
use SAREhub\Microt\App\AppBootstrap;
use SAREhub\Microt\App\AppRunOptions;
use SAREhub\Microt\App\AppRunOptionsProvider;
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

class SimpleAppRunOptionsProvider implements AppRunOptionsProvider
{

    public function get(): AppRunOptions
    {
        return new AppRunOptions(new ChainContainerConfigurator([
            new BasicContainerConfigurator(),
            new SimpleContainerConfigurator()
        ]), new SimpleMiddlewareInjector());
    }
}

AppBootstrap::create(new SimpleAppRunOptionsProvider())->run();
