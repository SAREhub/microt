<?php


namespace SAREhub\Microt\Util\Request;


use Slim\Http\Request;
use Slim\Http\Response;

class RequestEnricherMiddleware
{
    /**
     * @var RequestEnricher
     */
    private $enricher;

    public function __construct(RequestEnricher $enricher)
    {
        $this->enricher = $enricher;
    }

    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $request = $this->enricher->enrich($request);
        return $next($request, $response);
    }
}