<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Generator;

use Exception;
use Throwable;
use ReflectionMethod;
use ReflectionParameter;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

final class ReflectionAction
{
    /**
     * Controller method instance.
     *
     * @var \ReflectionMethod
     */
    protected $method;

    /**
     * Form request parameter.
     *
     * @var \Sandulat\R3doc\Generator\FormRequest|null
     */
    protected $formRequest;

    /**
     * Instantiate a new action reflection instance.
     *
     * @param string $controllerClass
     * @param string $controllerMethod
     */
    public function __construct(string $controllerClass, string $controllerMethod)
    {
        $this->method = new ReflectionMethod($controllerClass, $controllerMethod);

        $this->parseFormRequest();
    }

    /**
     * Parse controller method form request parameter.
     *
     * @return self
     */
    public function parseFormRequest(): void
    {
        foreach ($this->method->getParameters() as $parameter) {
            if (! $this->skipParameter($parameter)) {
                $formRequestClass = $parameter->getType()->getName();

                $this->formRequest = new FormRequest(new $formRequestClass());

                break;
            }
        }
    }

    /**
     * Check whether the parameter should be skipped.
     *
     * @param \ReflectionParameter $parameter
     * @return bool
     */
    public function skipParameter(ReflectionParameter $parameter): bool
    {
        try {
            $typeClass = $parameter->getType()->getName();
        
            $parameterInstance = new $typeClass();

            return ! is_subclass_of($parameterInstance, BaseFormRequest::class);
        } catch (Throwable $t) {
            return true;
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * Get form request.
     *
     * @return \Sandulat\R3doc\Generator\FormRequest|null
     */
    public function getFormRequest(): ?FormRequest
    {
        return $this->formRequest;
    }
}
