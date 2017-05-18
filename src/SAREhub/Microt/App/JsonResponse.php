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

namespace SAREhub\Microt\App;

use JSend\JSendResponse;
use Slim\Http\Response;

class JsonResponse {
	
	private $orginal;
	private $encodingOptions = \JSON_PRETTY_PRINT;
	
	public function __construct(Response $orginal) {
		$this->orginal = $orginal;
	}
	
	public static function wrap(Response $orginalResponse): self {
		return new self($orginalResponse);
	}
	
	public function ok(array $data = null) {
		return $this->success($data, 200);
	}
	
	public function created(array $data = null): Response {
		return $this->success($data, 201);
	}
	
	public function success(array $data = null, int $status): Response {
		return $this->create(JSendResponse::success($data), $status);
	}
	
	public function badRequest(array $data) {
		return $this->fail($data, 400);
	}
	
	public function notFound(array $data = null): Response {
		return $this->fail($data, 404);
	}
	
	public function fail(array $data, int $statusCode): Response {
		return $this->create(JSendResponse::fail($data), $statusCode);
	}
	
	public function internalServerError(string $message, array $data) {
		return $this->error($message, $data, 500);
	}
	
	public function error(string $message, array $data = null, $statusCode): Response {
		return $this->create(JSendResponse::error($message, null, $data), $statusCode);
	}
	
	private function create(JSendResponse $json, int $status) {
		return $this->orginal->withJson($json, $status, $this->encodingOptions);
	}
}