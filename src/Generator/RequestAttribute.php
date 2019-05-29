<?php

declare(strict_types=1);

namespace Sandulat\R3doc\Generator;

use Illuminate\Validation\Rules\In as EnumRule;

final class RequestAttribute
{
    /**
     * Attribute name.
     *
     * @var string
     */
    protected $name;

    /**
     * Attribute required rule.
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Attribute nullable rule.
     *
     * @var bool
     */
    protected $nullable = false;

    /**
     * Attribute enum.
     *
     * @var array
     */
    protected $enum;

    /**
     * Attribute type.
     *
     * @var string
     */
    protected $type = 'string';

    /**
     * Form requests attributes
     *
     * @var \Sandulat\R3doc\Generator\RequestAttributeCollection
     */
    protected $attributes;

    /**
     * Instantiate a new form request instance.
     */
    public function __construct(string $name, array $rules)
    {
        $this->name = $name;

        $this->attributes = new RequestAttributeCollection();

        $this->parseRules($rules);
    }

    /**
     * Parse form request rules.
     *
     * @param array $rules
     * @return self
     */
    public function parseRules(array $rules): void
    {
        if (in_array('required', $rules)) {
            $this->required = true;
        } elseif (in_array('nullable', $rules)) {
            $this->nullable = true;
        }

        $types = ['string', 'integer', 'boolean', 'array'];

        foreach ($types as $type) {
            if (in_array($type, $rules)) {
                $this->type = $type;

                break;
            }
        }

        $this->parseEnumRules($rules);
    }

    /**
     * Parse form request rules.
     *
     * @param array $rules
     * @return self
     */
    public function parseEnumRules(array $rules): void
    {
        $inRule = 'in';
        
        if (array_key_exists($inRule, $rules)) {
            $result = explode(',', $rules[$inRule]);
        }

        foreach ($rules as $rule) {
            if (is_string($rule) && starts_with($rule, $inRule . ':') || $rule instanceof EnumRule) {
                $result = explode(',', explode(':', (string) $rule)[1]);

                break;
            }
        }

        if (isset($result)) {
            $this->enum = str_replace('"', '', $result);
        }
    }

    /**
     * Get attribute name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get required state.
     *
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }

    /**
     * Get nullable state.
     *
     * @return bool
     */
    public function getNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * Get attribute type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get attribute enum.
     *
     * @return array|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    /**
     * Set attribute name.
     *
     * @param string $name
     * @return self
     */
    public function changeName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return \Sandulat\R3doc\Generator\RequestAttributeCollection
     */
    public function getAttributes(): RequestAttributeCollection
    {
        return $this->attributes;
    }
}
