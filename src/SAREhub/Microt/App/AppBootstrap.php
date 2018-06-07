<?php


namespace SAREhub\Microt\App;


class AppBootstrap
{
    /**
     * @var ServiceAppFactory
     */
    private $appFactory;

    /**
     * @var AppRunOptions
     */
    private $runOptions;

    public function __construct(AppRunOptions $runOptions, ServiceAppFactory $appFactory)
    {
        $this->runOptions = $runOptions;
        $this->appFactory = $appFactory;
    }

    public static function create(AppRunOptions $runOptions, ?ServiceAppFactory $appFactory = null): self
    {
        return new self($runOptions, $appFactory ?? new ServiceAppFactory());
    }

    /**
     * Creates and runs app
     */
    public function run(): void
    {
        try {
            $this->createApp()->run();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function createApp(): ServiceApp
    {
        $app = $this->appFactory->create($this->runOptions->getContainerConfigurator());
        $this->runOptions->getMiddlewareInjector()->injectTo($app);
        return $app;
    }

    private function handleException(\Throwable $e): void
    {
        $basicApp = $this->appFactory->createBasic();
        $basicApp->respondWithInternalErrorResponse($e);
    }
}