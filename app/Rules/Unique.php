<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Unique implements ValidationRule
{
    private Builder $query;
    private string $column;
    private array $exceptions;

    public function __construct(Builder $query, string $column, 
            array $exceptions = [], exceptKey = null)
    {
        $this->query = $query;
        $this->column = $column;
        $this->exceptions = $exceptions;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($this->exceptions) && in_array($value, 
                $this->exceptions)) return;
        $query = clone $this->query;
        $query->where($this->column, $value);
        if ($exceptKey !== null) {
            $query->whereKeyNot($exceptKey);
        }
        if (!$query->exists()) return;
        $fail('The selected :attribute is invalid.');
    }
}
