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


use Pimple\Container;
use SAREhub\Microt\Util\JsonResponse;

class MethodNotAllowedHandlerProvider implements ServiceProvider {
	
	const ENTRY = 'notAllowedHandler';
	
	public function register(Container $c) {
		$c[self::ENTRY] = function ($c) {
			return function ($rq, $resp, $methods) use ($c) {
				return JsonResponse::wrap($c['response'])
				  ->error('method not allowed', ['allowedMethods' => $methods], 405)
				  ->withHeader('Allow', implode(', ', $methods));
			};
		};
	}
}