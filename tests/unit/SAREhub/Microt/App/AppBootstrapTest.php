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
     * @var ContainerConfigurator
     */
    private $containerConfigurator;

    /**
     * @var MockInterface | MiddlewareInjector
     */
    private $middlewareInjector;

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
        $this->containerConfigurator = \Mockery::mock(ContainerConfigurator::class);
        $this->middlewareInjector = \Mockery::mock(MiddlewareInjector::class)->shouldIgnoreMissing();
        $runOptions = new AppRunOptions($this->containerConfigurator, $this->middlewareInjector);
        $this->appFactory = \Mockery::mock(ServiceAppFactory::class);

        $this->bootstrap = new AppBootstrap($runOptions, $this->appFactory);
    }

    public function testRun()
    {
        $app = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("create")->withArgs([$this->containerConfigurator])->andReturn($app);
        $this->middlewareInjector->expects("injectTo")->withArgs([$app]);
        $app->expects("run");

        $this->bootstrap->run();
    }

    public function testRunWhenAppFactoryCreateThrowsExceptionThenRespondWithInternalError()
    {
        $this->middlewareInjector->shouldIgnoreMissing();
        $exception = new \Exception("error");
        $this->appFactory->expects("create")->withArgs([$this->containerConfigurator])->andThrow($exception);

        $this->expectsBasicAppRespondWithInternalErrorResponse($exception);

        $this->bootstrap->run();
    }

    public function testRunWhenMiddlewareInjectorInjectToThrowsExceptionThenRespondWithInternalError()
    {
        $app = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("create")->withArgs([$this->containerConfigurator])->andReturn($app);
        $exception = new \Exception("error");
        $this->middlewareInjector->expects("injectTo")->withArgs([$app])->andThrow($exception);

        $this->expectsBasicAppRespondWithInternalErrorResponse($exception);

        $this->bootstrap->run();
    }

    public function testRunWhenAppRunThrowsExceptionThenRespondWithInternalError()
    {
        $this->middlewareInjector->shouldIgnoreMissing();
        $app = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("create")->withArgs([$this->containerConfigurator])->andReturn($app);
        $exception = new \Exception("error");
        $app->expects("run")->withArgs([])->andThrow($exception);

        $this->expectsBasicAppRespondWithInternalErrorResponse($exception);

        $this->bootstrap->run();
    }

    private function expectsBasicAppRespondWithInternalErrorResponse(\Throwable $e)
    {
        $basicApp = \Mockery::mock(ServiceApp::class);
        $this->appFactory->expects("createBasic")->andReturn($basicApp);
        $basicApp->expects("respondWithInternalErrorResponse")->withArgs([$e]);
    }
}
