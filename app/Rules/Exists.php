<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class Exists implements ValidationRule
{
    private Builder $query;
    private string $column;
    private array $exceptions;

    public function __construct(Builder $query, string $column, 
            array $exceptions = [])
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
        if ($query->where($this->column, $value)->exists()) return;
        $fail('The selected :attribute is invalid.');
    }
}
