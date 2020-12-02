<?php

declare(strict_types=1);

namespace App\Shared\Domain\FilterCriteria\Condition;

abstract class AbstractFilterCondition
{
    protected string $field;
    protected $value;
    protected string $operator;

    public function __construct(string $field, $value, string $operator)
    {
        $this->field = $field;
        $this->value = $value;
        $this->operator = $operator;
    }

    public function field(): string
    {
        return $this->field;
    }

    public function value()
    {
        return $this->value;
    }

    public function operator(): string
    {
        return $this->operator;
    }
}
