<?php

namespace SAREhub\Microt\HealthCheck;

use SAREhub\Microt\App\Controller\Controller;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class HealthCheckController implements Controller
{
    /**
     * @var HealthCheckCommand
     */
    private $command;

    public function __construct(HealthCheckCommand $command)
    {
        $this->command = $command;
    }

    public function healthAction(Request $request, Response $response): Response
    {
        $result = $this->command->perform();
        return JsonResponse::wrap($response)->create($result, $result->getStatusCode());
    }
}
