<?php


namespace SAREhub\Microt\App\Route;

use Slim\Interfaces\RouteInterface;

interface RouteMiddlewareInjector {
	
	public function injectTo(RouteInterface $route);
}