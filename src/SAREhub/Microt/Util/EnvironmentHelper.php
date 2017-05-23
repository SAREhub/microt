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


class EnvironmentHelper {
	
	public static function getVar(string $name, $default = null) {
		$value = getenv($name);
		return $value !== false ? $value : $default;
	}
	
	public static function getVars(array $schema): array {
		$env = [];
		foreach ($schema as $name => $default) {
			$env[$name] = self::getVar($name, $default);
		}
		
		return $env;
	}
}