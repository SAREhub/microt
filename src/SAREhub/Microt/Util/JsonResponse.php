<?php
/**
 * Copyright 2017 SARE S.A
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 *
 */

namespace SAREhub\Microt\Util;

use Slim\Http\Response;

class JsonResponse {
	
	private $orginal;
	private $encodingOptions = \JSON_PRETTY_PRINT;
	
	public function __construct(Response $orginal) {
		$this->orginal = $orginal;
	}
	
	public static function wrap(Response $orginal): self {
		return new self($orginal);
	}
	
	public function ok(array $body = null) {
		return $this->create($body, 200);
	}
	
	public function created(array $body = null): Response {
		return $this->create($body, 201);
	}
	
	public function badRequest(string $message, array $details = null) {
		return $this->error($message, $details, 400);
	}
	
	public function notFound(string $message, array $details = null): Response {
		return $this->error($message, $details, 404);
	}
	
	public function internalServerError(string $message, array $details = null) {
		return $this->error($message, $details, 500);
	}
	
	public function error(string $message, array $details = null, $statusCode): Response {
		$body = self::createErrorBody($message, $details);
		return $this->create($body, $statusCode);
	}
	
	public static function createErrorBody(string $message, array $details = null) {
		return [
		  'message' => $message,
		  'details' => $details
		];
	}
	
	private function create(array $body, int $status) {
		return $this->orginal->withJson($body, $status, $this->encodingOptions);
	}
}