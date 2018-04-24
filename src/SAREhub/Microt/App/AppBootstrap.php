<?php


namespace SAREhub\Microt\App;


use SAREhub\Microt\App\Middleware\MiddlewareInjector;

class AppBootstrap
{
    /**
     * @var ServiceAppFactory
     */
    private $appFactory;

    public function __construct(ServiceAppFactory $appFactory)
    {
        $this->appFactory = $appFactory;
    }

    public function run(ContainerConfigurator $containerConfigurator, MiddlewareInjector $injector)
    {
        try {
            $app = $this->appFactory->create($containerConfigurator);
            $injector->injectTo($app);
            $app->run();
        } catch (\Throwable $e) {
            $basicApp = $this->appFactory->createBasic();
            $basicApp->respondWithInternalErrorResponse($e);
        }

    }
}