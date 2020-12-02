<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Entities;

class Where
{
    protected string $columnName;
    protected string $operator;
    protected string $value;

    public function __construct(string $columnName, string $operator, string $value)
    {
        $this->columnName = $columnName;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
