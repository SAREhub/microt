<?php


namespace SAREhub\Microt\App;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Response;

class ErrorHandler
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $rq, Response $resp, \Throwable $e)
    {
        $this->logger->error($e->getMessage(), ["exception" => $e]);
        return JsonResponse::wrap($resp)->internalServerError("exception occur");
    }

}
