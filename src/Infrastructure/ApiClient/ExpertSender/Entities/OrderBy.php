<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Entities;

class OrderBy
{
    protected string $columnName;
    protected string $direction;

    public function __construct(string $columnName, string $direction)
    {
        $this->columnName = $columnName;
        $this->direction = $direction;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
