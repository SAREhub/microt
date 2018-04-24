<?php

namespace SAREhub\Microt\App;


use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class MethodNotAllowedHandlerProvider extends InvokableProvider
{

    public function get()
    {
        return function (Request $rq, Response $resp, $methods) {
            return JsonResponse::wrap($resp)
                ->error('method not allowed', ['allowedMethods' => $methods], 405)
                ->withHeader('Allow', implode(', ', $methods));
        };
    }
}