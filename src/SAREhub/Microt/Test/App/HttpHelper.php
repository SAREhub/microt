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

namespace SAREhub\Microt\Test\App;


use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Route;

class HttpHelper {
	
	public static function requestWithQuery(array $query): Request {
		return self::request()->withQueryParams($query);
	}
	
	public static function requestWithAttribute(string $name, $value): Request {
		return self::request()->withAttribute($name, $value);
	}
	
	public static function requestWithJson(array $data): Request {
		return self::requestWithBody(json_encode($data))->withHeader('Content-Type', 'application/json');
	}
	
	public static function requestWithBody($body = ''): Request {
		$req = self::request()->withBody(new RequestBody());
		$req->getBody()->write($body);
		$req->getBody()->rewind();
		return $req;
	}
	
	public static function request(): Request {
		return Request::createFromEnvironment(Environment::mock());
	}
	
	public static function response(): Response {
		return new Response();
	}
	
	public static function routeWithArguments(array $arguments): Route {
		$route = new Route('GET', 'pattern', function () { });
		return $route->setArguments($arguments);
	}
}