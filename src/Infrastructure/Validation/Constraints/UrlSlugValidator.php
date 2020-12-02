<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UrlSlugValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!$value || !$this->isValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

    private function isValid(string $value): bool
    {
        return (bool) (preg_match('/^[a-z0-9\-]+$/', $value));
    }
}
