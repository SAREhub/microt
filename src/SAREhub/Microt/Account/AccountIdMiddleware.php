<?php


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