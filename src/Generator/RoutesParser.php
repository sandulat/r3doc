<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Generator;

use Illuminate\Routing\Route as BaseRoute;
use Illuminate\Support\Facades\Route as Router;

final class RoutesParser
{
    /**
     * Application's routes.
     *
     * @var \Sandulat\R3doc\Generator\Route[]
     */
    protected $routes = [];

    /**
     * Routes to be blacklisted.
     *
     * @var string[]
     */
    private $blacklistRoutes = [];

    /**
     * Routes to be whitelisted.
     *
     * @var string[]
     */
    private $whitelistRoutes = [];

    /**
     * Instantiate a new routes parser instance.
     */
    public function __construct(array $blacklistRoutes = [], array $whitelistRoutes = [])
    {
        $this->blacklistRoutes = $blacklistRoutes;

        $this->whitelistRoutes = $whitelistRoutes;
    }

    /**
     * Parse all the declared routes.
     *
     * @return self
     */
    public function parse(): self
    {
        /** @var \Illuminate\Routing\Route[] $routes */
        $routes = Router::getRoutes()->get();

        foreach ($routes as $route) {
            if (! $this->skipRoute($route)) {
                $this->parseRouteMethods($route);
            }
        }

        return $this;
    }

    /**
     * Parse route by HTTP methods.
     *
     * @param \Illuminate\Routing\Route $route
     * @return void
     */
    protected function parseRouteMethods(BaseRoute $route): void
    {
        foreach ($route->methods() as $httpMethod) {
            $this->routes[] = new Route($route, $httpMethod);
        }
    }

    /**
     * Get the parsed routes.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Check whether the route should be skipped.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function skipRoute(BaseRoute $route): bool
    {
        $action = $route->getAction();

        $skip = is_object($action['uses']);

        foreach ($this->whitelistRoutes as $whitelistedRoute) {
            if (! fnmatch($whitelistedRoute, $route->uri())) {
                $skip = true;
            }
        }

        foreach ($this->blacklistRoutes as $blacklistedRoute) {
            if (fnmatch($blacklistedRoute, $route->uri())) {
                $skip = true;
            }
        }

        return $skip;
    }
}
