<?php

declare(strict_types=1);

namespace App\Application\Customer\FindByEmail;

use App\Domain\Customer\Service\CustomerByEmailFinder;

final class FindByEmailQueryHandler
{
    private CustomerByEmailFinder $finder;

    public function __construct(CustomerByEmailFinder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(FindByEmailQuery $query): FindByEmailResponse
    {
        $customer = $this->finder->__invoke($query->email());

        return (new FindByEmailResponseConverter())->__invoke($customer);
    }
}
