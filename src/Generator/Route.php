<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Generator;

use Illuminate\Routing\Route as BaseRoute;

final class Route
{
    /**
     * Route controller.
     *
     * @var string
     */
    protected $uri;

    /**
     * Route controller.
     *
     * @var string
     */
    protected $controller;

    /**
     * Route controller method.
     *
     * @var string
     */
    protected $controllerMethod;

    /**
     * Route HTTP method.
     *
     * @var string
     */
    protected $httpMethod;

    /**
     * Route action instance.
     *
     * @var \Sandulat\R3doc\Generator\ReflectionAction
     */
    protected $action;

    /**
     * Instantiate a new route instance.
     *
     * @param \Illuminate\Routing\Route $route
     */
    public function __construct(BaseRoute $route, string $httpMethod)
    {
        $action = $route->getAction();

        [$controllerClass, $controllerMethod] = explode('@', $action['uses']);

        $this->controller = $controllerClass;

        $this->controllerMethod = $controllerMethod;

        $this->httpMethod = $httpMethod;

        $this->uri = $route->uri();

        $this->action = new ReflectionAction($controllerClass, $controllerMethod);
    }

    /**
     * Get route endpoint.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get route HTTP method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Get request attributes.
     *
     * @return \Sandulat\R3doc\Generator\RequestAttributeCollection|null
     */
    public function getAttributes(): ?RequestAttributeCollection
    {
        $formRequest = $this->action->getFormRequest();

        return $formRequest ? $formRequest->getAttributes() : null;
    }
}
