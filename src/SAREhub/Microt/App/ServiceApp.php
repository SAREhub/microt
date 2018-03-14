<?php


namespace SAREhub\Microt\App;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Slim\Http\Request;
use Slim\Http\Response;

class ServiceApp extends App
{
    /**
     * @var ContainerConfigurator
     */
    private $containerConfigurator;

    public function __construct(ContainerConfigurator $containerConfigurator)
    {
        $this->containerConfigurator = $containerConfigurator;
        parent::__construct();
    }

    protected function configureContainer(ContainerBuilder $builder)
    {
        $this->containerConfigurator->configure($builder);
    }

    public function respondWithInternalErrorResponse(\Throwable $e)
    {
        $errorHandler = $this->getErrorHandler();
        $request = $this->getRequest();
        $response = $this->getResponse();
        $this->respond($errorHandler($request, $response, $e));
    }

    public function getErrorHandler(): callable
    {
        return $this->errorHandler;
    }

    public function getRequest(): Request
    {
        return $this->getContainer()->get("request");
    }

    public function getResponse(): Response
    {
        return $this->getContainer()->get("response");
    }
}
