<?php

declare(strict_types=1);

namespace App\Domain\Customer\Exception;

use App\Shared\Domain\DomainError;

final class CustomerAlreadyExistsException extends DomainError
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;

        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'customer_already_exists';
    }

    public function errorMessage(): string
    {
        return sprintf('Customer with "%s" already exists.', $this->value);
    }
}
