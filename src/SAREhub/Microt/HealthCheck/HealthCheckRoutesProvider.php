<?php

namespace SAREhub\Microt\HealthCheck;

use SAREhub\Microt\App\Controller\ControllerActionRoutes;
use SAREhub\Microt\App\Controller\ControllerRoutesProvider;

class HealthCheckRoutesProvider extends ControllerRoutesProvider
{
    /**
     * @var string
     */
    private $baseUri;

    public function __construct(string $baseUri, array $middlewares = [])
    {
        parent::__construct($middlewares);
        $this->baseUri = $baseUri;
    }


    protected function getBaseUri(): string
    {
        return $this->baseUri;
    }

    protected function getControllerClass(): string
    {
        return HealthCheckController::class;
    }

    protected function injectRoutes(ControllerActionRoutes $routes)
    {
        $routes->get("/health", "health");
    }
}