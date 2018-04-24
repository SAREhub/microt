<?php

namespace SAREhub\Microt\App;

use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class NotFoundHandlerProvider extends InvokableProvider
{

    public function get()
    {
        return function (Request $rq, Response $resp) {
            return JsonResponse::wrap($resp)->notFound("route not found check api documentation");
        };
    }
}