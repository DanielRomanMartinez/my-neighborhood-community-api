<?php

declare(strict_types=1);

namespace App\Domain\Customer\Exception;

use App\Shared\Domain\DomainError;

final class CustomerNotFoundException extends DomainError
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;

        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'customer_not_found';
    }

    public function errorMessage(): string
    {
        return sprintf('Customer with "%s" not found.', $this->value);
    }
}
