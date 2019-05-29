<?php

declare(strict_types=1);

namespace Sandulat\R3doc;

use Sandulat\R3doc\Generator\RoutesParser;

final class R3doc
{
    /**
     * Parsed routes.
     *
     * @var \Sandulat\R3doc\Generator\Route[]
     */
    protected $routes = [];

    /**
     * Instantiate a new documentation generator instance.
     */
    public function __construct()
    {
        $routesParser = new RoutesParser(
            config('r3doc.blacklistRoutes'),
            config('r3doc.whitelistRoutes'),
        );

        $this->routes = $routesParser->parse()->getRoutes();
    }

    /**
     * Get parsed routes.
     *
     * @var \Sandulat\R3doc\Generator\Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
