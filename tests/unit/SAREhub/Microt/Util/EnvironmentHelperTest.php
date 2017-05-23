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

use PHPUnit\Framework\TestCase;

class EnvironmentHelperTest extends TestCase {
	
	const VARIABLE = 'ENV_HELPER_TEST';
	
	public function setUp() {
		putenv(self::VARIABLE);
	}
	
	public function testGetVarWhenExists() {
		putenv(self::VARIABLE.'=1');
		$this->assertEquals(1, EnvironmentHelper::getVar(self::VARIABLE));
	}
	
	public function testGetVarWhenNotExists() {
		$this->assertEquals(1, EnvironmentHelper::getVar(self::VARIABLE, 1));
	}
	
	public function testGetVarsWhenExists() {
		putenv(self::VARIABLE.'=1');
		$this->assertEquals([self::VARIABLE => 1], EnvironmentHelper::getVars([self::VARIABLE => 2]));
	}
	
	public function testGetVarsWhenNotExists() {
		$this->assertEquals([self::VARIABLE => 2], EnvironmentHelper::getVars([self::VARIABLE => 2]));
	}
}
