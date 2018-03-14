<?php


namespace SAREhub\Microt\App;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

class RequestRouteAttributesSetterInvoker implements InvocationStrategyInterface
{

    /**
     * @var InvocationStrategyInterface
     */
    private $nextInvoker;

    public function __construct(InvocationStrategyInterface $nextInvoker)
    {
        $this->nextInvoker = $nextInvoker;
    }

    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    )
    {
        $request = $request->withAttributes($routeArguments);
        return ($this->nextInvoker)($callable, $request, $response, $routeArguments);
    }
}
