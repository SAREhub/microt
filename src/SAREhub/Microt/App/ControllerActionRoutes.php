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

use Slim\App;

class ControllerActionRoutes implements MiddlewareInjector {
	
	private $baseUri;
	private $controllerClass;
	
	private $routes = [];
	
	public function __construct(string $baseUri, string $controllerClass) {
		$this->baseUri = $baseUri;
		$this->controllerClass = $controllerClass;
	}
	
	public static function create(string $baseUri, string $controllerClass): ControllerActionRoutes {
		return new self($baseUri, $controllerClass);
	}
	
	public function addRoute(ControllerActionRoute $r): self {
		$r->controllerClass($this->getControllerClass());
		$r->pattern($this->getBaseUri().$r->getPattern());
		$this->routes[] = $r;
		return $this;
	}
	
	public function injectTo(App $app) {
		foreach ($this->getAll() as $route) {
			$route->injectTo($app);
		}
	}
	
	public function getBaseUri(): string {
		return $this->baseUri;
	}
	
	public function getControllerClass(): string {
		return $this->controllerClass;
	}
	
	/**
	 * @return ControllerActionRoute[]
	 */
	public function getAll(): array {
		return $this->routes;
	}
	
}