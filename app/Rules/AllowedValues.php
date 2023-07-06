<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedValues implements ValidationRule
{
    private array $allowedValues;

    /**
     * Create a new rule instance.
     *
     * @param array $allowedValues
     */
    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    /**
     * Validate the attribute value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value)) {
            $invalidValues = array_diff($value, $this->allowedValues);
            if (!empty($invalidValues)) {
                $fail("The {$attribute} field must contain only the following values: " . implode(", ", $this->allowedValues) . ".");
            }
        } elseif (!in_array($value, $this->allowedValues)) {
            $fail("The {$attribute} field must be one of the following values: " . implode(", ", $this->allowedValues) . ".");
        }
    }
}
