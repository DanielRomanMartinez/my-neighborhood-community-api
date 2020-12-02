<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Entities;

class Column
{
    protected string $name;
    protected ?string $value;

    public function __construct(string $name, ?string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
