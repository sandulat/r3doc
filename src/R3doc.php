<?php

declare(strict_types=1);

namespace Sandulat\R3doc;

use Sandulat\R3doc\Generator\RoutesParser;
use Sandulat\R3doc\Generator\RequestAttribute;
use Sandulat\R3doc\Generator\RequestAttributeCollection;

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
            config('r3doc.whitelistRoutes')
        );

        $this->routes = $routesParser->parse()->getRoutes();
    }

    /**
     * Get parsed routes in JSON format.
     *
     * @return string
     */
    public function getJsonRoutes(): string
    {
        $data = [];

        foreach ($this->routes as $route) {
            $item = [
                'uri' => $route->getUri(),
                'method' => $route->getMethod(),
                'attributes' => $this->requestRecursiveAttributes($route->getAttributes()),
            ];

            $data[] = $item;
        }

        return json_encode($data);
    }

    /**
     * Parse request attribute.
     *
     * @param \Sandulat\R3doc\Generator\RequestAttribute $attribute
     * @return array
     */
    public function parseRequestAttribute(RequestAttribute $attribute): array
    {
        return [
            'name' => $attribute->getName(),
            'required' => $attribute->getRequired(),
            'nullable' => $attribute->getNullable(),
            'enum' => $attribute->getEnum(),
            'type' => $attribute->getType(),
        ];
    }

    /**
     * Get request recursive attributes.
     *
     * @param \Sandulat\R3doc\Generator\RequestAttributeCollection $collection
     * @return array
     */
    public function requestRecursiveAttributes(?RequestAttributeCollection $collection): array
    {
        if (! $collection) {
            return [];
        }

        $parsedAttributes = [];

        if ($attributes = $collection->getItems()) {
            foreach ($attributes as $attribute) {
                $nestedAttributes = $this->requestRecursiveAttributes($attribute->getAttributes());

                $parsedAttributes[] = array_merge(
                    $this->parseRequestAttribute($attribute),
                    ['attributes' => $nestedAttributes]
                );
            }
        }

        return $parsedAttributes;
    }
}
