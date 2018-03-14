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


class AppBootstrap
{
    /**
     * @var ServiceAppFactory
     */
    private $appFactory;

    public function __construct(ServiceAppFactory $appFactory)
    {
        $this->appFactory = $appFactory;
    }

    public function run(ContainerConfigurator $containerConfigurator, MiddlewareInjector $injector)
    {
        try {
            $app = $this->appFactory->create($containerConfigurator);
            $injector->injectTo($app);
            $app->run();
        } catch (\Throwable $e) {
            $basicApp = $this->appFactory->createBasic();
            $basicApp->respondWithInternalErrorResponse($e);
        }

    }
}