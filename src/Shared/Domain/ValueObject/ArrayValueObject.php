<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract class ArrayValueObject
{
    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function value(): array
    {
        return $this->value;
    }
}
