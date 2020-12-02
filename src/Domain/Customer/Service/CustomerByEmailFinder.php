<?php

declare(strict_types=1);

namespace App\Domain\Customer\Service;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Customer\Exception\CustomerByEmailNotFoundException;

final class CustomerByEmailFinder
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(string $email): Customer
    {
        $customer = $this->customerRepository->findByEmail($email);

        if (!$customer) {
            throw new CustomerByEmailNotFoundException($email);
        }

        return  $customer;
    }
}
