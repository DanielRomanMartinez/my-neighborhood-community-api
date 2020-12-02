<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class OptionValueValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!$constraint instanceof OptionValue) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\OptionValue');
        }

        if (!$constraint->callback || !is_callable($constraint->callback)) {
            throw new UnexpectedTypeException($constraint->callback, 'callback');
        }

        $options = call_user_func($constraint->callback);
        if (!is_array($options)) {
            throw new UnexpectedTypeException($options, 'array');
        }

        $optionValues = $this->getValuesFromOptions($options);

        if (!$this->isValid($value, $optionValues)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(OptionValue::INVALID_OPTION_ERROR)
                ->addViolation();
        }
    }

    private function isValid($value, array $optionValues): bool
    {
        return in_array($value, $optionValues);
    }

    private function getValuesFromOptions(array $options): array
    {
        return array_column($options, 'value');
    }
}
