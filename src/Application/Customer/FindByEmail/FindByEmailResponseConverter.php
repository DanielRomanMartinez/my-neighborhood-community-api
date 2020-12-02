<?php

declare(strict_types=1);

namespace App\Application\Customer\FindByEmail;

use App\Domain\Customer\Customer;

final class FindByEmailResponseConverter
{
    public function __invoke(Customer $customer): FindByEmailResponse
    {
        return new FindByEmailResponse($customer->toArray());
    }
}
