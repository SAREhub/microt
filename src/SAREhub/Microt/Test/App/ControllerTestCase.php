<?php


namespace SAREhub\Microt\Test\App;

use PHPUnit\Framework\TestCase;
use SAREhub\Microt\App\BasicController;
use SAREhub\Microt\App\Controller\Controller;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class ControllerTestCase extends TestCase {

    protected function callAction(Controller $controller, string $action, Request $request, Response $response = null): Response
    {
        return $controller->{$action . 'Action'}($request, $response ?? HttpHelper::response());
	}
	
	protected function assertResponseCode(int $expected, Response $response) {
		$this->assertEquals($expected, $response->getStatusCode(), 'response status code');
	}
	
	protected function assertJsonResponse(int $expectedCode, $expectedBody, Response $response) {
		$this->assertResponseCode($expectedCode, $response);
		$this->assertJsonStringEqualsJsonString(json_encode($expectedBody, JSON_PRETTY_PRINT), (string)$response->getBody(), 'json response body');
	}
	
	protected function assertErrorJsonResponse(int $expectedCode, string $expectedMessage, $expectedDetails, Response $response) {
		$this->assertJsonResponse($expectedCode, JsonResponse::createErrorBody($expectedMessage, $expectedDetails), $response);
	}
	
}