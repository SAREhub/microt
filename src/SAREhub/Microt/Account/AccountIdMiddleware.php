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

namespace SAREhub\Microt\Account;

use Pimple\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;

class AccountIdMiddleware {
	
	const REQUEST_ROUTE_ATTRIBUTE = 'route';
	
	const ACCOUNT_ID_ENTRY = 'accountId';
	
	private $container;
	
	public function __construct(Container $container) {
		$this->container = $container;
	}
	
	public function __invoke(Request $request, Response $response, callable $next) {
		$id = $this->extractFromRequest($request);
		if (!empty($id)) {
			$this->container[self::ACCOUNT_ID_ENTRY] = $id;
		}
		
		return $next($request, $response);
	}
	
	private function extractFromRequest(Request $request): string {
		$route = $request->getAttribute(self::REQUEST_ROUTE_ATTRIBUTE);
		if ($route instanceof Route) {
			return $route->getArgument(self::ACCOUNT_ID_ENTRY, '');
		}
		
		return '';
	}
}