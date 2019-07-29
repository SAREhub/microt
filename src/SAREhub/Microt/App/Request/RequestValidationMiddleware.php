<?php


namespace SAREhub\Microt\App\Request;


use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use SAREhub\Microt\Util\DataValidationException;
use SAREhub\Microt\Util\ValidatorHelper;
use Slim\App;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class RequestValidationMiddleware implements MiddlewareInjector
{
    /**
     * @var RequestValidator
     */
    private $validator;

    /**
     * @var ValidatorHelper
     */
    private $helper;

    public function __construct(RequestValidator $validator, ?ValidatorHelper $helper = null)
    {
        $this->validator = $validator;
        $this->helper = $helper ?? new ValidatorHelper();
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        $route = $request->getAttribute("route");
        if (empty($route)) {
            throw new NotFoundException($request, $response);
        }

        try {
            $this->validator->assert($request);
            return $next($request, $response);
        } catch (DataValidationException $e) {
            return $this->helper->createBadRequestJsonResponse("Bad request", $e, $response);
        }
    }

    public function injectTo(App $app)
    {
        $app->add($this);
    }
}
