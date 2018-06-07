<?php


namespace SAREhub\Microt\Test;


use Mockery\MockInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class CallableMock
{
    public function __invoke(Request $request, Response $response)
    {

    }

    /**
     * @return MockInterface | callable
     */
    public static function create(): callable
    {
        return \Mockery::mock(self::class);
    }
}