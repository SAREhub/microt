<?php


namespace SAREhub\Microt\App;


use Throwable;

class AppBootstrap
{
    /**
     * @var ServiceAppFactory
     */
    private $appFactory;

    /**
     * @var AppRunOptionsProvider
     */
    private $runOptionsProvider;

    public function __construct($runOptionsProvider, ?ServiceAppFactory $appFactory = null)
    {
        $this->runOptionsProvider = $runOptionsProvider;
        $this->appFactory = $appFactory ?? new ServiceAppFactory();
    }

    public static function create(AppRunOptionsProvider $runOptionsProvider, ?ServiceAppFactory $appFactory = null): self
    {
        return new self($runOptionsProvider, $appFactory);
    }

    /**
     * Creates and runs app
     */
    public function run(): void
    {
        try {
            $this->createApp()->run();
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    private function createApp(): ServiceApp
    {
        $runOptions = $this->runOptionsProvider->get();
        $app = $this->appFactory->create($runOptions->getContainerConfigurator());
        $runOptions->getMiddlewareInjector()->injectTo($app);
        return $app;
    }

    private function handleException(Throwable $e): void
    {
        $basicApp = $this->appFactory->createBasic();
        $basicApp->respondWithInternalErrorResponse($e);
    }
}