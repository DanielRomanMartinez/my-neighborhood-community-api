<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

final class ValidationRulesFactory
{
    public function create(string $name): AbstractValidationRules
    {
        $className = '\App\Infrastructure\Validation\ValidationRules\\' . $name;

        if (!class_exists($className)) {
            throw new \Exception(sprintf('Class "%s" not found', $className));
        }

        $object = new $className;

        if (!$object instanceof AbstractValidationRules) {
            throw new \Exception(sprintf('Class %s must extend AbstractValidationRules', $className));
        }

        return $object;
    }
}
