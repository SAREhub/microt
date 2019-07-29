<?php


namespace SAREhub\Microt\App\Request;


use SAREhub\Microt\Util\DataValidationException;
use Slim\Http\Request;

interface RequestValidator
{
    /**
     * @param Request $request
     * @return bool
     * @throws DataValidationException
     */
    public function assert(Request $request): bool;

    public function getName(): string;
}