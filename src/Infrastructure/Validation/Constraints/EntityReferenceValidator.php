<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class EntityReferenceValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!$constraint instanceof EntityReference) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\EntityReference');
        }

        if (!$this->isValid($constraint->entityClass, $constraint->entityField, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(EntityReference::INVALID_REFERENCE_ERROR)
                ->addViolation();
        }
    }

    private function isValid(string $entityClass, string $entityField, $value): bool
    {
        $entry = $this->em->getRepository($entityClass)->findOneBy([$entityField => $value]);

        return $entry !== null;
    }
}
