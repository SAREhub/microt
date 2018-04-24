<?php


namespace SAREhub\Microt\Util;

use Slim\Http\Response;

class JsonResponse {
	
	private $orginal;
	private $encodingOptions = \JSON_PRETTY_PRINT;
	
	public function __construct(Response $orginal) {
		$this->orginal = $orginal;
	}
	
	public static function wrap(Response $orginal): JsonResponse {
		return new self($orginal);
	}
	
	public function ok($body): Response {
		return $this->create($body, 200);
	}
	
	public function created($body): Response {
		return $this->create($body, 201);
	}
	
	public function noContent(): Response {
		return $this->orginal->withStatus(204);
	}
	
	public function success($body, int $statusCode = 200): Response {
		return $this->create($body, $statusCode);
	}
	
	public function badRequest(string $message, array $details = null): Response {
		return $this->error($message, $details, 400);
	}
	
	public function notFound(string $message, array $details = null): Response {
		return $this->error($message, $details, 404);
	}
	
	public function internalServerError(string $message, array $details = null): Response {
		return $this->error($message, $details, 500);
	}
	
	public function error(string $message, array $details = null, $statusCode): Response {
		$body = self::createErrorBody($message, $details);
		return $this->create($body, $statusCode);
	}
	
	public static function createErrorBody(string $message, array $details = null): array {
		return [
		  'message' => $message,
		  'details' => $details
		];
	}
	
	private function create($body, int $status) {
		return $this->orginal->withJson($body, $status, $this->encodingOptions);
	}
}