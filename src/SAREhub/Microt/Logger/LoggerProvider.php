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

namespace SAREhub\Microt\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use SAREhub\Microt\App\AppBootstrap;
use SAREhub\Microt\App\ServiceProvider;

class LoggerProvider implements ServiceProvider {
	
	const ENTRY = 'logger';
	
	public function register(Container $c) {
		$handlers = $this->createHandlers($c);
		$processors = $this->createProcessors($c);
		$c[self::ENTRY] = new Logger($c[AppBootstrap::APP_NAME_ENTRY], $handlers, $processors);
	}
	
	protected function createHandlers(Container $c): array {
		return [$this->createStdoutHandler($c)];
	}
	
	protected function createStdoutHandler(Container $c): StreamHandler {
		$output = new StreamHandler('php://stdout');
		$formatter = new StandardLogFormatter();
		$output->setFormatter($formatter);
	}
	
	protected function createProcessors(Container $c): array {
		return [$this->createRequestIdProcessor($c)];
	}
	
	protected function createRequestIdProcessor(Container $c): RequestIdProcessor {
		if ($c['request']->hasHeader('X-Request-ID')) {
			return new RequestIdProcessor($c['request']->getHeader('X-Request-ID')[0]);
		}
		
		new RequestIdProcessor(0);
	}
}