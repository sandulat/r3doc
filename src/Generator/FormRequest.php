<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Generator;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

final class FormRequest
{
    /**
     * Base form request instance.
     *
     * @var \Illuminate\Foundation\Http\FormRequest
     */
    protected $baseFormRequest;

    /**
     * Form requests attributes.
     *
     * @var \Sandulat\R3doc\Generator\RequestAttributeCollection
     */
    protected $attributes;

    /**
     * Instantiate a new form request instance.
     */
    public function __construct(BaseFormRequest $baseFormRequest)
    {
        $this->baseFormRequest = $baseFormRequest;

        $this->attributes = new RequestAttributeCollection();

        $this->parseAttributes();
    }

    /**
     * Parse form request attributes.
     *
     * @return void
     */
    public function parseAttributes(): void
    {
        foreach ($this->baseFormRequest->rules() as $attribute => $rules) {
            $attributeRules = is_string($rules) ? explode('|', $rules) : $rules;

            $this->attributes->add(new RequestAttribute($attribute, $attributeRules));
        }

        $this->attributes->nestAttributes();
    }

    /**
     * Get attributes.
     *
     * @return string
     */
    public function getAttributes(): RequestAttributeCollection
    {
        return $this->attributes;
    }
}
