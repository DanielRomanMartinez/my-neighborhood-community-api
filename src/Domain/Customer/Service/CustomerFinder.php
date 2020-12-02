<?php

declare(strict_types=1);

namespace App\Domain\Customer\Service;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

final class CustomerFinder
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Uuid $id): Customer
    {
        $customer = $this->repository->find($id);

        if (null === $customer) {
            throw new CustomerNotFoundException($id->value());
        }

        return $customer;
    }
}
