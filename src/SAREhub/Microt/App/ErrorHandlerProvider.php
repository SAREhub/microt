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
use SAREhub\Microt\Logger\AppLoggerProvider;
use SAREhub\Microt\Util\JsonResponse;

class ErrorHandlerProvider implements ServiceProvider {
	
	const RUNTIME_ERROR_HANDLER_ENTRY = 'phpErrorHandler';
	const ERROR_HANDLER = 'errorHandler';
	
	public function register(Container $c) {
		$c[self::RUNTIME_ERROR_HANDLER_ENTRY] = $c[self::ERROR_HANDLER] = function ($c) {
			return function ($rq, $resp, \Throwable $e) use ($c) {
                $c[AppLoggerProvider::class]->error($e->getMessage(), ['exception' => $e]);
				return JsonResponse::wrap($c['response'])->internalServerError('exception occur');
			};
		};
	}
}