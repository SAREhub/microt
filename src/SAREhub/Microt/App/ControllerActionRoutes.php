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

class ControllerActionRoutes implements MiddlewareInjector, \JsonSerializable {
	
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
	
	public function post(string $pattern, string $action): ControllerActionRoute {
		$route = ControllerActionRoute::post($pattern, $action);
		$this->addRoute($route);
		return $route;
	}
	
	public function get(string $pattern, string $action): ControllerActionRoute {
		$route = ControllerActionRoute::get($pattern, $action);
		$this->addRoute($route);
		return $route;
	}
	
	public function put(string $pattern, string $action): ControllerActionRoute {
		$route = ControllerActionRoute::put($pattern, $action);
		$this->addRoute($route);
		return $route;
	}
	
	public function patch(string $pattern, string $action): ControllerActionRoute {
		$route = ControllerActionRoute::patch($pattern, $action);
		$this->addRoute($route);
		return $route;
	}
	
	public function delete(string $pattern, string $action): ControllerActionRoute {
		$route = ControllerActionRoute::delete($pattern, $action);
		$this->addRoute($route);
		return $route;
	}
	
	public function addRoute(ControllerActionRoute $r): self {
		$r->controller($this->getController());
		$r->pattern($this->getBaseUri().$r->getPattern());
		$this->routes[] = $r;
		return $this;
	}
	
	public function injectTo(App $app) {
		foreach ($this->getRoutes() as $route) {
			$route->injectTo($app);
		}
	}
	
	public function getBaseUri(): string {
		return $this->baseUri;
	}
	
	public function getController(): string {
		return $this->controllerClass;
	}
	
	/**
	 * @return ControllerActionRoute[]
	 */
	public function getRoutes(): array {
		return $this->routes;
	}
	
	public function jsonSerialize() {
		return [
		  'baseUri' => $this->getBaseUri(),
		  'controller' => $this->getController(),
		  'routes' => $this->getRoutes()
		];
	}
}