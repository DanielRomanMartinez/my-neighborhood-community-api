<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\ConstraintFactory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;

final class ConstraintValidatorFactory extends \Symfony\Component\Validator\ConstraintValidatorFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getInstance(Constraint $constraint)
    {
        $validatedBy = $constraint->validatedBy();
        if ($this->container->has($validatedBy)) {
            return $this->container->get($validatedBy);
        }

        return parent::getInstance($constraint);
    }
}
