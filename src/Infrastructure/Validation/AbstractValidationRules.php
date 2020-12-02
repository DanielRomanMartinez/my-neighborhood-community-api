<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

abstract class AbstractValidationRules
{
    public function groups(array $data): ?array
    {
        return null;
    }

    abstract public function rules(): array;
}
