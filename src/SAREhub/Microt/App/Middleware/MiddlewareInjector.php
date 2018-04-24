<?php


namespace SAREhub\Microt\App\Middleware;


use Slim\App;

interface MiddlewareInjector {
	
	public function injectTo(App $app);
}