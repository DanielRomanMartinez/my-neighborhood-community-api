<?php

declare(strict_types=1);

namespace App\Application\Customer\Create;

use App\Domain\Customer\Exception\CustomerAlreadyExistsException;
use App\Domain\Customer\Exception\CustomerByEmailNotFoundException;
use App\Domain\Customer\Service\CustomerByEmailFinder;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;

final class CreateCustomerUseCase
{
    private CustomerByEmailFinder $customerFinder;
    private CustomerCreator $creator;

    public function __construct(
        CustomerByEmailFinder $customerFinder,
        CustomerCreator $creator
    ) {
        $this->customerFinder = $customerFinder;
        $this->creator = $creator;
    }

    public function __invoke(
        string $customerId,
        string $email,
        string $encodedPassword,
        string $firstName,
        string $middleName,
        string $lastName,
        DateTime $createdAt,
        DateTime $updatedAt
    ): void {

        try {
            $customer = $this->customerFinder->__invoke($email);

            if (null !== $customer) {
                throw new CustomerAlreadyExistsException($email);
            }
        } catch (CustomerByEmailNotFoundException $exception) {
            $customer = null;
        }

        $this->creator->__invoke(
            new Uuid($customerId),
            $email,
            $encodedPassword,
            $firstName,
            $middleName,
            $lastName,
            $createdAt,
            $updatedAt
        );
    }
}
