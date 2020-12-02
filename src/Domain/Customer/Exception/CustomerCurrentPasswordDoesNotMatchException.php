<?php

declare(strict_types=1);

namespace App\Domain\Customer\Exception;

use App\Shared\Domain\DomainError;

final class CustomerCurrentPasswordDoesNotMatchException extends DomainError
{
    public function errorCode(): string
    {
        return 'customer_current_password_does_not_match';
    }

    public function errorMessage(): string
    {
        return sprintf('Current password does not match.');
    }
}
