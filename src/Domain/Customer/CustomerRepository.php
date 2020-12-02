<?php

declare(strict_types=1);

namespace App\Domain\Customer;

use App\Shared\Domain\FilterCriteria\FilterCriteriaBuilderInterface;
use App\Shared\Domain\ValueObject\Uuid;

interface CustomerRepository
{
    public function create(Customer $customer): void;

    public function update(Customer $customer): void;

    public function find(Uuid $id): ?Customer;

    public function findByEmail(string $email): ?Customer;

    public function getFilterCriteriaBuilder(): FilterCriteriaBuilderInterface;
}
