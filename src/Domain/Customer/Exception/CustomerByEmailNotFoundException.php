<?php

declare(strict_types=1);

namespace App\Domain\Customer\Exception;

use App\Shared\Domain\DomainError;

final class CustomerByEmailNotFoundException extends DomainError
{
    private String $email;

    public function __construct(String $email)
    {
        $this->email = $email;
        parent::__construct();
    }

    public function errorCode():  string
    {
        return 'customer_by_email_not_found';
    }

    public function errorMessage(): string
    {
        return sprintf('User with email <%s> not found.', $this->email);
    }
}
