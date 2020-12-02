<?php

namespace App\Infrastructure\Validation;

interface ValidatorInterface
{
    public function validate(array $data, AbstractValidationRules $validationRules): void;

    public function isValid(): bool;

    public function getErrors(): array;
}
