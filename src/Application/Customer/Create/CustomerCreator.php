<?php

declare(strict_types=1);

namespace App\Application\Customer\Create;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

final class CustomerCreator
{
    private CustomerRepository $repository;

    public function __construct(
        CustomerRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke(
        Uuid $id,
        string $email,
        string $password,
        ?string $firstName,
        ?string $middleName,
        ?string $lastName,
        DateTime $createdAt,
        DateTime $updatedAt
    ): void {
        $customer = Customer::create(
            $id,
            $email,
            $password,
            $firstName,
            $middleName,
            $lastName,
            null,
            $createdAt,
            $updatedAt
        );

        $this->repository->create($customer);
    }
}
