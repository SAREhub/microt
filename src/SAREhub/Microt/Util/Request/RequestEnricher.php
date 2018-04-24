<?php


namespace SAREhub\Microt\Util\Request;


use Slim\Http\Request;

interface RequestEnricher
{
    public function enrich(Request $request): Request;
}