<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class EntityReference extends Constraint
{
    const INVALID_REFERENCE_ERROR = 'ec25165b-a53e-4105-a852-be54293670a5';

    protected static $errorNames = [
        self::INVALID_REFERENCE_ERROR => 'INVALID_REFERENCE_ERROR',
    ];

    public string $message = 'This value is not a valid reference.';

    public string $entityClass;
    public string $entityField;

    public function __construct($options = null)
    {
        $this->entityClass = $options['entity_class'] ?? null;
        $this->entityField = $options['entity_field'] ?? 'id';

        if (!$this->entityClass) {
            throw new MissingOptionsException(sprintf('Option "entity_class" is required for constraint %s', __CLASS__), ['entity_class']);
        }

        unset($options['entity_class'], $options['entity_field']);

        parent::__construct($options);
    }

    public function validatedBy()
    {
        return EntityReferenceValidator::class;
    }
}
