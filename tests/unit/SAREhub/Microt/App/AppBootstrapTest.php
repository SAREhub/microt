<?php


namespace SAREhub\Microt\App;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\App\Middleware\MiddlewareInjector;

class AppBootstrapTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | ServiceAppFactory
     */
    private $appFactory;

    /**
     * @var AppBootstrap
     */
    private $bootstrap;

    protected function setUp()
    {
        $this->appFactory = \Mockery::mock(ServiceAppFactory::class);
        $this->bootstrap = new AppBootstrap($this->appFactory);
    }

    public function testRunThenAppFactoryCreate()
    {
        $containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $middlewareInjector = \Mockery::mock(MiddlewareInjector::class)->shouldIgnoreMissing();

        $app = \Mockery::mock(ServiceApp::class)->shouldIgnoreMissing();
        $this->appFactory->expects("create")->withArgs([$containerConfigurator])->andReturn($app);

        $this->bootstrap->run($containerConfigurator, $middlewareInjector);
    }

    public function testRunThenMiddlewareInjector()
    {
        $containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $middlewareInjector = \Mockery::mock(MiddlewareInjector::class);

        $app = \Mockery::mock(ServiceApp::class)->shouldIgnoreMissing();
        $this->appFactory->shouldIgnoreMissing($app);

        $middlewareInjector->expects("injectTo")->withArgs([$app]);

        $this->bootstrap->run($containerConfigurator, $middlewareInjector);
    }

    public function testRunThenAppRun()
    {
        $containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $middlewareInjector = \Mockery::mock(MiddlewareInjector::class)->shouldIgnoreMissing();

        $app = \Mockery::mock(ServiceApp::class);
        $this->appFactory->shouldIgnoreMissing($app);

        $app->expects("run");

        $this->bootstrap->run($containerConfigurator, $middlewareInjector);
    }

    public function testRunWhenAppFactoryCreateThrowsExceptionThenRespondWithInternalError()
    {
        $containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $middlewareInjector = \Mockery::mock(MiddlewareInjector::class)->shouldIgnoreMissing();

        $basicApp = \Mockery::mock(ServiceApp::class);
        $exception = new \Exception("error");
        $this->appFactory->expects("create")->withArgs([$containerConfigurator])->andThrow($exception);
        $this->appFactory->expects("createBasic")->andReturn($basicApp);

        $basicApp->expects("respondWithInternalErrorResponse")->withArgs([$exception]);

        $this->bootstrap->run($containerConfigurator, $middlewareInjector);
    }

    public function testRunWhenMiddlewareInjectorInjectToThrowsExceptionThenRespondWithInternalError()
    {
        $containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $middlewareInjector = \Mockery::mock(MiddlewareInjector::class);

        $app = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("create")->withArgs([$containerConfigurator])->andReturn($app);

        $exception = new \Exception("error");
        $middlewareInjector->expects("injectTo")->withArgs([$app])->andThrow($exception);

        $basicApp = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("createBasic")->andReturn($basicApp);
        $basicApp->expects("respondWithInternalErrorResponse")->withArgs([$exception]);

        $this->bootstrap->run($containerConfigurator, $middlewareInjector);
    }

    public function testRunWhenAppRunThrowsExceptionThenRespondWithInternalError()
    {
        $containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $middlewareInjector = \Mockery::mock(MiddlewareInjector::class)->shouldIgnoreMissing();

        $app = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("create")->withArgs([$containerConfigurator])->andReturn($app);

        $exception = new \Exception("error");
        $app->expects("run")->withArgs([])->andThrow($exception);
        $basicApp = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("createBasic")->andReturn($basicApp);

        $basicApp->expects("respondWithInternalErrorResponse")->withArgs([$exception]);

        $this->bootstrap->run($containerConfigurator, $middlewareInjector);
    }
}
