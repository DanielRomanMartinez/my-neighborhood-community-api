<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

final class Validator implements ValidatorInterface
{
    private bool $isValid;
    private array $errors = [];
    private ErrorsNormalizerInterface $errorsNormalizer;
    private ConstraintValidatorFactoryInterface $constraintValidatorFactory;
    private SymfonyValidatorInterface $validator;

    public function __construct(
        ConstraintValidatorFactoryInterface $constraintValidatorFactory,
        ErrorsNormalizerInterface $errorsNormalizer
    ) {
        $this->errorsNormalizer = $errorsNormalizer;
        $this->constraintValidatorFactory = $constraintValidatorFactory;

        $this->validator = $this->buildValidator();
    }

    public function validate(array $data, AbstractValidationRules $validationRules): void
    {
        $this->errors = [];

        $rules = $validationRules->rules();
        $groups = $validationRules->groups($data);

        foreach ($rules as $field => $constraints) {
            $value = $data[$field] ?? null;
            $violations = $this->validator->validate($value, $constraints, $groups);

            if ($violations->count() > 0) {
                $this->errors[$field] = $this->buildErrorsList($violations);
            }
        }

        $this->isValid = (count($this->errors) === 0);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function buildErrorsList(ConstraintViolationListInterface $violationList)
    {
        return $this->errorsNormalizer->normalize($violationList);
    }

    private function buildValidator(): SymfonyValidatorInterface
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        $validatorBuilder->setConstraintValidatorFactory($this->constraintValidatorFactory);

        return $validatorBuilder->getValidator();
    }
}
