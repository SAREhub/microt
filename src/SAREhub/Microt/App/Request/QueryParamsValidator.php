<?php


namespace SAREhub\Microt\App\Request;


use Respect\Validation\Validator;
use SAREhub\Microt\Util\ValidatorHelper;
use Slim\Http\Request;

class QueryParamsValidator implements RequestValidator
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var ValidatorHelper
     */
    private $helper;

    public function __construct(Validator $validator, ?ValidatorHelper $helper = null)
    {
        $this->validator = $validator;
        $this->helper = $helper ?? new ValidatorHelper();
    }

    public function assert(Request $request): bool
    {
        $this->helper->assert($this->validator, $request->getQueryParams());
        return true;
    }

    public function getName(): string
    {
        return "Request.QueryParams";
    }
}